<?php

/**
 * CActiveTreeDataProvider class.
 *
 * PHP version 5
 * 
 * @category   Widgets
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 */
/**
 * CActiveTreeDataProvider is the dataprovider class
 * for fetching tree-structure based data (adjacency model).
 * 
 * @category   Widgets
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 */
class CActiveTreeDataProvider extends CActiveDataProvider
{
    const KEY_ID = 'id';
    const KEY_TEXT = 'text';
    const KEY_HAS_CHILDREN = 'hasChildren';
    const KEY_CHILDREN = 'children';

    /**
     * Root node id.
     *
     * @var int
     */
    public $rootNodeId;

    /**
     * Tree data cache.
     *
     * @var array
     */
    protected $_treeData;

    /**
     * Additional fields in callback.
     *
     * @var array (
     *            'index' => 'fieldName',
     *            )
     */
    public $additional;

    /**
     * Tree data making options.
     *
     * @var array
     */
    public $treeDataOptions;

    /**
     * Constructs class based object.
     * 
     * @param string $modelClass model's class name
     * @param array  $config     provider's config
     */
    public function __construct($modelClass, $config = array())
    {
        parent::__construct($modelClass, $config);
        $this->treeDataOptions = isset($this->treeDataOptions) ? $this->treeDataOptions : array();
        $this->treeDataOptions = CMap::mergeArray(array(
            'keys' => array(
                //self::KEY_ID => 'my_id',
                //self::KEY_TEXT => 'my_text',
                //...
            ),
            'columns' => array(
                //'id' => 'my_id',
                //'name' => 'my_name',
                //...
            ),
        ), $this->treeDataOptions);
    }

    /**
     * Fetchs raw data.
     * 
     * @return array fetched data
     *
     * @see CActiveDataProvider::fetchData()
     */
    protected function fetchData()
    {
        $criteria = clone $this->getCriteria();

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->setItemCount($this->getTotalItemCount());
            $pagination->applyLimit($criteria);
        }

        if (($sort = $this->getSort()) !== false) {
            $sort->applyOrder($criteria);
        }

        $baseCriteria = $this->model->getDbCriteria(false);
        if ($baseCriteria !== null) {
            $baseCriteria = clone $baseCriteria;
        }
        $tableName = $this->model->tableName();
        $data = Yii::app()->db->getCommandBuilder()->createFindCommand($tableName, $criteria)->queryAll();
        $this->model->setDbCriteria($baseCriteria);

        return $data;
    }

    /**
     * Returns parent tree item.
     * 
     * @param array tree item
     * 
     * @return array root node item
     */
    public function getParentNodeItem($treeItem)
    {
        $parentIdColumn = $this->resolveColumnName('parent_id');
        $idColumn = $this->resolveColumnName('id');
        if (isset($treeItem[$parentIdColumn])) {
            $criteria = new CDbCriteria();
            $criteria->compare('t.'.$idColumn, $treeItem[$parentIdColumn]);
            $tableName = $this->model->tableName();

            return Yii::app()->db->getCommandBuilder()->createFindCommand($tableName, $criteria)->queryRow();
        }

        return;
    }

    /**
     * Returns data in treeview control's format.
     * 
     * @return array tree data
     */
    public function getTreeData()
    {
        if (!isset($this->_treeData)) {
            $levels = array();
            $items = $this->getData();
            $parentIdColumn = $this->resolveColumnName('parent_id');
            $levelColumn = $this->resolveColumnName('level');

            $minLevel = null;
            $minLevelParent = null;
            foreach ($items as $item) {
                $dataItem = $this->getTreeItemData($item);
                if ($dataItem === null) {
                    continue;
                }
                $parentKey = $item[$parentIdColumn] === null ? 'null' : $item[$parentIdColumn];
                if (!isset($levels[$item[$levelColumn]])) {
                    $levels[$item[$levelColumn]] = array();
                }
                $levels[$item[$levelColumn]][$parentKey][] = $dataItem;
                if (!isset($minLevel) || $item[$levelColumn] < $minLevel) {
                    $minLevel = $item[$levelColumn];
                    $minLevelParent = $item[$parentIdColumn];
                }
            }

            $levelKeys = array_keys($levels);
            if (empty($levelKeys)) {
                $this->_treeData = array();
            } else {
                $parent = isset($minLevelParent) ? $minLevelParent : 'null';
                $this->_treeData = $this->getTreeDataRec($levels, $parent, $minLevel);
            }

            $this->postFilterTreeData();
        }

        return $this->_treeData;
    }

    /**
     * Postfilters tree data.
     */
    protected function postFilterTreeData()
    {
        $curItem = &$this->_treeData;
        $path = array();
        $current = $this->rootNodeId
                 ? $this->getParentNodeItem(array(
                      $this->resolveColumnName('parent_id') => $this->rootNodeId,
                   ))
                 : null;
        $idColumn = $this->resolveColumnName('id');
        while ($current) {
            $path[] = $current[$idColumn];
            $current = $this->getParentNodeItem($current);
        }
        $path = array_reverse($path);

        $count = count($path);
        $childrenKey = $this->resolveKeyName(self::KEY_CHILDREN);
        for ($i = 0; $i < $count; ++$i) {
            foreach ($curItem as $j => $item) {
                if ($item[$idColumn] == $path[$i]) {
                    if (isset($curItem[$j][$childrenKey])) {
                        $curItem = &$curItem[$j][$childrenKey];
                    } else {
                        $curItem = array();
                    }
                    break;
                }
            }
        }

        $this->_treeData = $curItem;
    }

    /**
     * Returns tree item data by record.
     * 
     * @param array $item tree item
     * 
     * @return array tree item data
     */
    public function getTreeItemData($item)
    {
        $treeItemData = array(
            $this->resolveKeyName(self::KEY_ID) => $item[$this->resolveColumnName('id')],
            $this->resolveKeyName(self::KEY_TEXT) => $item[$this->resolveColumnName('name')],
            $this->resolveKeyName(self::KEY_HAS_CHILDREN) => $item[$this->resolveColumnName('is_leaf')] == 0,
        );
        if (!empty($this->additional)) {
            foreach ($this->additional as $index => $fieldName) {
                $treeItemData[$index] = $item[$fieldName];
            }
        }

        return $treeItemData;
    }

    /**
     * Returns data in tree's data recursively.
     * 
     * @param array  $data   data array
     * @param string $parent parent element id
     * @param int    $level  item level
     * @param array  $data
     */
    protected function getTreeDataRec(&$data, $parent, $level)
    {
        if (!isset($data[$level]) || !isset($data[$level][$parent])) {
            return array();
        }
        $items = &$data[$level][$parent];
        $ret = array();
        $idColumn = $this->resolveColumnName('id');
        $keyHasChildren = $this->resolveKeyName(self::KEY_HAS_CHILDREN);
        $keyChildren = $this->resolveKeyName(self::KEY_CHILDREN);
        foreach ($items as $i => $item) {
            $ret[$i] = $item;
            if ($item[$keyHasChildren]) {
                $ret[$i][$keyChildren] = $this->getTreeDataRec($data, $item[$idColumn], $level + 1);
            }
        }

        return $ret;
    }

    /**
     * Resolves real model column name by given abstract model column name.
     * 
     * @param string $columnName abstract column name
     * 
     * @return string real column name of database table
     */
    protected function resolveColumnName($columnName)
    {
        return isset($this->treeDataOptions['columns'][$columnName])
             ? $this->treeDataOptions['columns'][$columnName]
             : $columnName;
    }

    /**
     * Resolves output tree data key name by given abstract tree data key name.
     * 
     * @param string $keyName abstract tree item key name
     * 
     * @return string real tree data key name
     */
    protected function resolveKeyName($keyName)
    {
        return isset($this->treeDataOptions['keys'][$keyName])
             ? $this->treeDataOptions['keys'][$keyName]
             : $keyName;
    }
}
