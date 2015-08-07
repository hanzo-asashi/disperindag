<?php
/**
 * NestedSetBehavior class file.
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Alexander Kochetov <creocoder@gmail.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://github.com/yiiext/nested-set-behavior
 */
/**
 * Provides nested set functionality for a model.
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Alexander Kochetov <creocoder@gmail.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @version    1.06
 * @link       https://github.com/yiiext/nested-set-behavior
 */
class NestedSetBehavior extends CActiveRecordBehavior
{
    /**
     * Whether tree has many roots
     * @var boolean
     */
    public $hasManyRoots = false;
    
    /**
     * Root attribute name
     * @var string
     */
    public $rootAttribute = 'root';
    
    /**
     * Left attribute name
     * @var string
     */
    public $leftAttribute = 'lft';
    
    /**
     * Right attribute name
     * @var string
     */
    public $rightAttribute = 'rgt';
    
    /**
     * Level attribute name
     * @var string
     */
    public $levelAttribute = 'level';
    
    /**
     * Whether required to ignore activerecord events
     * @var boolean
     */
    private $_ignoreEvent = false;
    
    /**
     * Whether item has been deleted
     * @var boolean
     */
    private $_deleted = false;
    
    /**
     * Record identifier
     * @var integer
     */
    private $_id;
    
    /**
     * Cached data
     * @var mixed
     */
    private static $_cached;
    
    /**
     * Counter
     * @var integer
     */
    private static $_c = 0;

    /**
     * @var
     */
    private $_root;

    /**
     * @var
     */
    private $_parent;

    /**
     * @var
     */
    private $_prev;

    /**
     * @var
     */
    private $_next;

    /**
     * Named scope. Gets descendants for node.
     *
     * @param int $depth the depth.
     *
     * @return CActiveRecord the owner.
     */
    public function descendants($depth = null)
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        $criteria = $owner->getDbCriteria();
        $alias = $db->quoteColumnName($owner->getTableAlias());

        $with = array(
            'condition' => $alias . '.' . $db->quoteColumnName($this->leftAttribute) . '>' . $owner->{$this->leftAttribute} .
                ' AND ' . $alias . '.' . $db->quoteColumnName($this->rightAttribute) . '<' . $owner->{$this->rightAttribute},
            'order' => $alias . '.' . $db->quoteColumnName($this->leftAttribute),
        );
        $criteria->mergeWith($with);

        if ($depth !== null) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->levelAttribute) . '<=' . ($owner->{$this->levelAttribute} + $depth));
        }

        if ($this->hasManyRoots) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
            $criteria->params[CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
        }

        return $owner;
    }

    /**
     * Gets descendants
     *
     * @return array descendants
     */
    public function findDescendants()
    {
        return $this->descendants()->findAll();
    }

    /**
     * Named scope. Gets children for node (direct descendants only).
     *
     * @return CActiveRecord the owner.
     */
    public function children()
    {
        return $this->descendants(1);
    }

    /**
     * Named scope. Gets ancestors for node.
     *
     * @param int $depth the depth.
     *
     * @return CActiveRecord the owner.
     */
    public function ancestors($depth = null)
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        $criteria = $owner->getDbCriteria();
        $alias = $db->quoteColumnName($owner->getTableAlias());

        $with = array(
            'condition' => $alias . '.' . $db->quoteColumnName($this->leftAttribute) . '<' . $owner->{$this->leftAttribute} .
                ' AND ' . $alias . '.' . $db->quoteColumnName($this->rightAttribute) . '>' . $owner->{$this->rightAttribute},
            'order' => $alias . '.' . $db->quoteColumnName($this->leftAttribute),
        );
        $criteria->mergeWith($with);

        if ($depth !== null) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->levelAttribute) . '>=' . ($owner->{$this->levelAttribute} - $depth));
        }

        if ($this->hasManyRoots) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount);
            $criteria->params[CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
        }

        return $owner;
    }

    /**
     * Named scope. Gets root node(s).
     * 
     * @return CActiveRecord the owner.
     */
    public function roots()
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        $owner->getDbCriteria()->addCondition($db->quoteColumnName($owner->getTableAlias()) . '.' . $db->quoteColumnName($this->leftAttribute) . '=1');

        return $owner;
    }

    /**
     * Gets root node(s) active record
     *
     * @return array root node active record
     */
    public function findRoots()
    {
        if (!$this->_root) {
            $this->_root = $this->roots()->find();
        }
        return $this->_root;
    }

    /**
     * Named scope. Gets parent of node.
     * 
     * @return CActiveRecord the owner.
     */
    public function parent()
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        $criteria = $owner->getDbCriteria();
        $alias = $db->quoteColumnName($owner->getTableAlias());

        $with = array(
            'condition' => $alias . '.' . $db->quoteColumnName($this->leftAttribute) . '<' . $owner->{$this->leftAttribute} .
                ' AND ' . $alias . '.' . $db->quoteColumnName($this->rightAttribute) . '>' . $owner->{$this->rightAttribute},
            'order' => $alias . '.' . $db->quoteColumnName($this->rightAttribute),
        );
        $criteria->mergeWith($with);

        if ($this->hasManyRoots) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount);
            $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
        }

        return $owner;
    }

    /**
     * Gets root node(s) active record
     *
     * @return array root node active record
     */
    public function findParent()
    {
        if (!$this->_parent) {
            $this->_parent = $this->parent()->find();
        }
        return $this->_parent;
    }

    /**
     * Named scope. Gets previous sibling of node.
     *
     * @return CActiveRecord the owner.
     */
    public function prev()
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        $criteria = $owner->getDbCriteria();
        $alias = $db->quoteColumnName($owner->getTableAlias());
        $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->rightAttribute) . '=' . ($owner->{$this->leftAttribute} - 1));

        if ($this->hasManyRoots) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount);
            $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
        }

        return $owner;
    }

    /**
     * Gets prev node(s) active record
     *
     * @return array root node active record
     */
    public function findPrev()
    {
        if (!$this->_prev) {
            $this->_prev = $this->prev()->find();
        }
        return $this->_prev;
    }

    /**
     * Named scope. Gets next sibling of node.
     *
     * @return CActiveRecord the owner.
     */
    public function next()
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        $criteria = $owner->getDbCriteria();
        $alias = $db->quoteColumnName($owner->getTableAlias());
        $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->leftAttribute) . '=' . ($owner->{$this->rightAttribute} + 1));

        if ($this->hasManyRoots) {
            $criteria->addCondition($alias . '.' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount);
            $criteria->params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
        }

        return $owner;
    }

    /**
     * Gets prev node(s) active record
     *
     * @return array root node active record
     */
    public function findNext()
    {
        if (!$this->_next) {
            $this->_next = $this->next()->find();
        }
        return $this->_next;
    }

    /**
     * Create root node if multiple-root tree mode. Update node if it's not new.
     *
     * @param boolean $runValidation whether to perform validation.
     * @param boolean $attributes    list of attributes.
     *
     * @return boolean whether the saving succeeds.
     */
    public function save($runValidation = true, $attributes = null)
    {
        $owner = $this->getOwner();

        if ($runValidation && !$owner->validate($attributes)) {
            return false;
        }

        if ($owner->getIsNewRecord()) {
            return $this->_makeRoot($attributes);
        }

        $this->_ignoreEvent = true;
        $result = $owner->update($attributes);
        $this->_ignoreEvent = false;

        return $result;
    }

    /**
     * Create root node if multiple-root tree mode. Update node if it's not new.
     *
     * @param boolean $runValidation whether to perform validation.
     * @param boolean $attributes    list of attributes.
     *
     * @return boolean whether the saving succeeds.
     */
    public function saveNode($runValidation = true, $attributes = null)
    {
        return $this->save($runValidation, $attributes);
    }

    /**
     * Deletes node and it's descendants.
     *
     * @return boolean whether the deletion is successful.
     */
    public function delete()
    {
        $owner = $this->getOwner();
        if ($owner->getIsNewRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node cannot be deleted because it is new.'));
        }
        if ($this->getIsDeletedRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node cannot be deleted because it is already deleted.'));
        }
        $db = $owner->getDbConnection();
        if ($db->getCurrentTransaction() === null) {
            $transaction = $db->beginTransaction();
        }

        try {
            if ($owner->isLeaf()) {
                $this->_ignoreEvent = true;
                $result = $owner->delete();
                $this->_ignoreEvent = false;
            } else {
                $condition = $db->quoteColumnName($this->leftAttribute) . '>=' . $owner->{$this->leftAttribute} . ' AND ' .
                $db->quoteColumnName($this->rightAttribute) . '<=' . $owner->{$this->rightAttribute};
                $params = array();
                if ($this->hasManyRoots) {
                    $condition .= ' AND ' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount;
                    $params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
                }
                $result = $owner->deleteAll($condition, $params) > 0;
            }

            if (!$result) {
                if (isset($transaction)) {
                    $transaction->rollBack();
                }
                return false;
            }

            $this->_shiftLeftRight($owner->{$this->rightAttribute} + 1, $owner->{$this->leftAttribute}-$owner->{$this->rightAttribute} - 1);
            if (isset($transaction)) {
                $transaction->commit();
            }
            $this->_correctCachedOnDelete();

        } catch(Exception $e) {
            if (isset($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        }

        return true;
    }

    /**
     * Deletes node and it's descendants.
     *
     * @return boolean whether the deletion is successful.
     */
    public function deleteNode()
    {
        return $this->delete();
    }

    /**
     * Prepends node to target as first child.
     *
     * @param CActiveRecord $target        the target.
     * @param boolean       $runValidation whether to perform validation.
     * @param array         $attributes    list of attributes.
     *
     * @return boolean whether the prepending succeeds.
     */
    public function prependTo($target, $runValidation = true, $attributes = null)
    {
        return $this->_addNode($target, $target->{$this->leftAttribute} + 1, 1, $runValidation, $attributes);
    }

    /**
     * Prepends target to node as first child.
     *
     * @param CActiveRecord $target        the target.
     * @param boolean       $runValidation whether to perform validation.
     * @param array         $attributes    list of attributes.
     *
     * @return boolean whether the prepending succeeds.
     */
    public function prepend($target, $runValidation = true, $attributes = null)
    {
        return $target->prependTo($this->getOwner(), $runValidation, $attributes);
    }

    /**
     * Appends node to target as last child.
     *
     * @param CActiveRecord $target        the target.
     * @param boolean       $runValidation whether to perform validation.
     * @param array         $attributes    list of attributes.
     *
     * @return boolean whether the appending succeeds.
     */
    public function appendTo($target, $runValidation = true, $attributes = null)
    {
        return $this->_addNode($target, $target->{$this->rightAttribute}, 1, $runValidation, $attributes);
    }

    /**
     * Appends target to node as last child.
     *
     * @param CActiveRecord $target        the target.
     * @param boolean       $runValidation whether to perform validation.
     * @param array         $attributes    list of attributes.
     *
     * @return boolean whether the appending succeeds.
     */
    public function append($target, $runValidation = true, $attributes = null)
    {
        return $target->appendTo($this->getOwner(), $runValidation, $attributes);
    }

    /**
     * Inserts node as previous sibling of target.
     *
     * @param CActiveRecord $target        the target.
     * @param boolean       $runValidation whether to perform validation.
     * @param array         $attributes    list of attributes.
     *
     * @return boolean whether the inserting succeeds.
     */
    public function insertBefore($target, $runValidation = true, $attributes = null)
    {
        return $this->_addNode($target, $target->{$this->leftAttribute}, 0, $runValidation, $attributes);
    }

    /**
     * Inserts node as next sibling of target.
     *
     * @param CActiveRecord $target        the target.
     * @param boolean       $runValidation whether to perform validation.
     * @param array         $attributes    list of attributes.
     *
     * @return boolean whether the inserting succeeds.
     */
    public function insertAfter($target, $runValidation = true, $attributes = null)
    {
        return $this->_addNode($target, $target->{$this->rightAttribute} + 1, 0, $runValidation, $attributes);
    }

    /**
     * Move node as previous sibling of target.
     *
     * @param CActiveRecord $target the target.
     *
     * @return boolean whether the moving succeeds.
     */
    public function moveBefore($target)
    {
        return $this->_moveNode($target, $target->{$this->leftAttribute}, 0);
    }

    /**
     * Move node as next sibling of target.
     *
     * @param CActiveRecord $target the target.
     *
     * @return boolean whether the moving succeeds.
     */
    public function moveAfter($target)
    {
        return $this->_moveNode($target, $target->{$this->rightAttribute} + 1, 0);
    }

    /**
     * Move node as first child of target.
     *
     * @param CActiveRecord $target the target.
     *
     * @return boolean whether the moving succeeds.
     */
    public function moveAsFirst($target)
    {
        return $this->_moveNode($target, $target->{$this->leftAttribute} + 1, 1);
    }

    /**
     * Move node as last child of target.
     *
     * @param CActiveRecord $target the target.
     *
     * @return boolean whether the moving succeeds.
     */
    public function moveAsLast($target)
    {
        return $this->_moveNode($target, $target->{$this->rightAttribute}, 1);
    }

    /**
     * Move node as new root.
     *
     * @return boolean whether the moving succeeds.
     */
    public function moveAsRoot()
    {
        $owner = $this->getOwner();

        if (!$this->hasManyRoots) {
            throw new CException(Yii::t('yiiext', 'Many roots mode is off.'));
        }
        if ($owner->getIsNewRecord()) {
            throw new CException(Yii::t('yiiext', 'The node should not be new record.'));
        }
        if ($this->getIsDeletedRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node should not be deleted.'));
        }
        if ($owner->isRoot()) {
            throw new CException(Yii::t('yiiext', 'The node already is root node.'));
        }
        $db = $owner->getDbConnection();
        if ($db->getCurrentTransaction() === null) {
            $transaction = $db->beginTransaction();
        }

        try
        {
            $left = $owner->{$this->leftAttribute};
            $right = $owner->{$this->rightAttribute};
            $levelDelta = 1 - $owner->{$this->levelAttribute};
            $delta = 1 - $left;

            $data = array(
                $this->leftAttribute => new CDbExpression($db->quoteColumnName($this->leftAttribute) . sprintf('%+d', $delta)),
                $this->rightAttribute => new CDbExpression($db->quoteColumnName($this->rightAttribute) . sprintf('%+d', $delta)),
                $this->levelAttribute => new CDbExpression($db->quoteColumnName($this->levelAttribute) . sprintf('%+d', $levelDelta)),
                $this->rootAttribute => $owner->getPrimaryKey(),
            );
            $owner->updateAll(
                $data,
                $db->quoteColumnName($this->leftAttribute) . '>=' . $left . ' AND ' .
                $db->quoteColumnName($this->rightAttribute) . '<=' . $right . ' AND ' .
                $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount,
                array(CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++ => $owner->{$this->rootAttribute})
            );

            $this->_shiftLeftRight($right + 1, $left - $right - 1);
            if (isset($transaction)) {
                $transaction->commit();
            }
            $this->_correctCachedOnMoveBetweenTrees(1, $levelDelta, $owner->getPrimaryKey());

        } catch(Exception $e) {
            if (isset($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        }

        return true;
    }

    /**
     * Determines if node is descendant of subject node.
     *
     * @param CActiveRecord $subj the subject node.
     *
     * @return boolean whether the node is descendant of subject node.
     */
    public function isDescendantOf($subj)
    {
        $owner = $this->getOwner();
        $result = ($owner->{$this->leftAttribute} > $subj->{$this->leftAttribute})
        && ($owner->{$this->rightAttribute} < $subj->{$this->rightAttribute});

        if ($this->hasManyRoots) {
            $result = $result && ($owner->{$this->rootAttribute} === $subj->{$this->rootAttribute});
        }

        return $result;
    }

    /**
     * Determines if node is leaf.
     *
     * @return boolean whether the node is leaf.
     */
    public function isLeaf()
    {
        $owner = $this->getOwner();
        return $owner->{$this->rightAttribute} - $owner->{$this->leftAttribute} === 1;
    }

    /**
     * Determines if node is root.
     *
     * @return boolean whether the node is root.
     */
    public function isRoot()
    {
        return $this->getOwner()->{$this->leftAttribute} == 1;
    }

    /**
     * Returns if the current node is deleted.
     *
     * @return boolean whether the node is deleted.
     */
    public function getIsDeletedRecord()
    {
        return $this->_deleted;
    }

    /**
     * Sets if the current node is deleted.
     *
     * @param boolean $value whether the node is deleted.
     *
     * @return void
     */
    public function setIsDeletedRecord($value)
    {
        $this->_deleted = $value;
    }

    /**
     * Handle 'afterConstruct' event of the owner.
     *
     * @param CEvent $event event parameter.
     *
     * @return void
     */
    public function afterConstruct($event)
    {
        $owner = $this->getOwner();
        self::$_cached[get_class($owner)][$this->_id = self::$_c++] = $owner;
    }

    /**
     * Handle 'afterFind' event of the owner.
     *
     * @param CEvent $event event parameter.
     *
     * @return void
     */
    public function afterFind($event)
    {
        $owner = $this->getOwner();
        self::$_cached[get_class($owner)][$this->_id = self::$_c++] = $owner;
    }

    /**
     * Handle 'beforeSave' event of the owner.
     *
     * @param CEvent $event event parameter.
     *
     * @return boolean.
     */
    public function beforeSave($event)
    {
        if ($this->_ignoreEvent) {
            return true;
        } else {
            throw new CDbException(Yii::t('yiiext', 'You should not use CActiveRecord::save() method when NestedSetBehavior attached.'));
        }
    }

    /**
     * Handle 'beforeDelete' event of the owner.
     *
     * @param CEvent $event event parameter.
     *
     * @return boolean.
     */
    public function beforeDelete($event)
    {
        if ($this->_ignoreEvent) {
            return true;
        } else {
            throw new CDbException(Yii::t('yiiext', 'You should not use CActiveRecord::delete() method when NestedSetBehavior attached.'));
        }
    }

    /**
     * Shifts left right keys
     *
     * @param int $key   node key
     * @param int $delta shift delta
     *
     * @return void
     */
    private function _shiftLeftRight($key, $delta)
    {
        $owner = $this->getOwner();
        $db = $owner->getDbConnection();
        foreach (array($this->leftAttribute, $this->rightAttribute) as $attribute) {
            $condition = $db->quoteColumnName($attribute) . '>=' . $key;
            $params = array();
            if ($this->hasManyRoots) {
                $condition .= ' AND ' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount;
                $params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
            }
            $owner->updateAll(array($attribute => new CDbExpression($db->quoteColumnName($attribute) . sprintf('%+d', $delta))), $condition, $params);
        }
    }

    /**
     * Adds new node into tree
     *
     * @param CActiveRecord $target        node to insert in
     * @param int           $key           node key
     * @param int           $levelUp       up level
     * @param boolean       $runValidation whether required to run validation before insert
     * @param array         $attributes    node attributes
     *
     * @return boolean whether operation completed successfully
     */
    private function _addNode($target, $key, $levelUp, $runValidation, $attributes)
    {
        $owner = $this->getOwner();
        /*if (!$owner->getIsNewRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node cannot be inserted because it is not new.'));
        }*/
        if ($this->getIsDeletedRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node cannot be inserted because it is deleted.'));
        }
        if ($target->getIsDeletedRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node cannot be inserted because target node is deleted.'));
        }
        if ($owner->equals($target)) {
            throw new CException(Yii::t('yiiext', 'The target node should not be self.'));
        }
        if (!$levelUp && $target->isRoot()) {
            throw new CException(Yii::t('yiiext', 'The target node should not be root.'));
        }
        if ($runValidation && !$owner->validate($attributes)) {
            return false;
        }
        if ($this->hasManyRoots) {
            $owner->{$this->rootAttribute} = $target->{$this->rootAttribute};
        }
        $db = $owner->getDbConnection();
        if ($db->getCurrentTransaction() === null) {
            $transaction = $db->beginTransaction();
        }

        try {
            $this->_shiftLeftRight($key, 2);
            $owner->{$this->leftAttribute} = $key;
            $owner->{$this->rightAttribute} = $key + 1;
            $owner->{$this->levelAttribute} = $target->{$this->levelAttribute} + $levelUp;
            $this->_ignoreEvent = true;
            $saveMethod = $owner->getIsNewRecord() ? 'insert' : 'update';
            $result = $owner->{$saveMethod}($attributes);
            $this->_ignoreEvent = false;
            if (!$result) {
                if (isset($transaction)) {
                    $transaction->rollBack();
                }
                return false;
            }
            if (isset($transaction)) {
                $transaction->commit();
            }
            $this->_correctCachedOnAddNode($key);

        } catch(Exception $e) {
            if (isset($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        }

        return true;
    }

    /**
     * Makes root node
     *
     * @param array $attributes node attributes
     *
     * @return boolean whether operation completed successfully
     */
    private function _makeRoot($attributes)
    {
        $owner = $this->getOwner();
        $owner->{$this->leftAttribute} = 1;
        $owner->{$this->rightAttribute} = 2;
        $owner->{$this->levelAttribute} = 1;

        if ($this->hasManyRoots) {
            $db = $owner->getDbConnection();
            if ($db->getCurrentTransaction() === null) {
                $transaction=$db->beginTransaction();
            }
            try {
                $this->_ignoreEvent = true;
                $result = $owner->insert($attributes);
                $this->_ignoreEvent = false;
                if (!$result) {
                    if (isset($transaction)) {
                        $transaction->rollBack();
                    }
                    return false;
                }
                $pk = $owner->{$this->rootAttribute} = $owner->getPrimaryKey();
                $owner->updateByPk($pk, array($this->rootAttribute => $pk));
                if (isset($transaction)) {
                    $transaction->commit();
                }
            } catch(Exception $e) {
                if (isset($transaction)) {
                    $transaction->rollBack();
                }
                throw $e;
            }
        } else {
            if ($owner->roots()->exists()) {
                throw new CException(Yii::t('yiiext', 'Cannot create more than one root in single root mode.'));
            }
            $this->_ignoreEvent = true;
            $result = $owner->insert($attributes);
            $this->_ignoreEvent = false;
            if (!$result) {
                return false;
            }
        }

        return true;
    }

    /**
     * Moves node
     *
     * @param CActiveRecord $target  node to move to
     * @param int           $key     node key
     * @param int           $levelUp node level
     *
     * @return boolean whether operation completed successfullt
     */
    private function _moveNode($target, $key, $levelUp)
    {
        $owner = $this->getOwner();
        if ($owner->getIsNewRecord()) {
            throw new CException(Yii::t('yiiext', 'The node should not be new record.'));
        }
        if ($this->getIsDeletedRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The node should not be deleted.'));
        }
        if ($target->getIsDeletedRecord()) {
            throw new CDbException(Yii::t('yiiext', 'The target node should not be deleted.'));
        }
        if ($owner->equals($target)) {
            throw new CException(Yii::t('yiiext', 'The target node should not be self.'));
        }
        if ($target->isDescendantOf($owner)) {
            throw new CException(Yii::t('yiiext', 'The target node should not be descendant.'));
        }
        if (!$levelUp && $target->isRoot()) {
            throw new CException(Yii::t('yiiext', 'The target node should not be root.'));
        }

        $db = $owner->getDbConnection();
        if ($db->getCurrentTransaction()===null) {
            $transaction = $db->beginTransaction();
        }

        try {
            $left = $owner->{$this->leftAttribute};
            $right = $owner->{$this->rightAttribute};
            $levelDelta = $target->{$this->levelAttribute} - $owner->{$this->levelAttribute} + $levelUp;
            if ($this->hasManyRoots && $owner->{$this->rootAttribute} !== $target->{$this->rootAttribute}) {
                foreach (array($this->leftAttribute, $this->rightAttribute) as $attribute) {
                    $data = array($attribute => new CDbExpression($db->quoteColumnName($attribute) . sprintf('%+d', $right - $left + 1)));
                    $owner->updateAll(
                        $data,
                        $db->quoteColumnName($attribute) . '>=' . $key . ' AND ' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount,
                        array(CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++ => $target->{$this->rootAttribute})
                    );
                }
                $delta = $key - $left;

                $data = array(
                    $this->leftAttribute => new CDbExpression($db->quoteColumnName($this->leftAttribute) . sprintf('%+d', $delta)),
                    $this->rightAttribute => new CDbExpression($db->quoteColumnName($this->rightAttribute) . sprintf('%+d', $delta)),
                    $this->levelAttribute => new CDbExpression($db->quoteColumnName($this->levelAttribute) . sprintf('%+d', $levelDelta)),
                    $this->rootAttribute => $target->{$this->rootAttribute},
                );
                $owner->updateAll(
                    $data,
                    $db->quoteColumnName($this->leftAttribute) . '>=' . $left . ' AND ' .
                    $db->quoteColumnName($this->rightAttribute) . '<=' . $right . ' AND ' .
                    $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount,
                    array(CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++ => $owner->{$this->rootAttribute})
                );

                $this->_shiftLeftRight($right + 1, $left - $right - 1);

                if (isset($transaction)) {
                    $transaction->commit();
                }

                $this->_correctCachedOnMoveBetweenTrees($key, $levelDelta, $target->{$this->rootAttribute});
            } else {
                $delta = $right - $left + 1;
                $this->_shiftLeftRight($key, $delta);
                if ($left >= $key) {
                    $left += $delta;
                    $right += $delta;
                }
                $condition = $db->quoteColumnName($this->leftAttribute) . '>=' . $left . ' AND ' . $db->quoteColumnName($this->rightAttribute) . '<=' . $right;
                $params = array();

                if ($this->hasManyRoots) {
                    $condition .= ' AND ' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount;
                    $params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
                }

                $owner->updateAll(array($this->levelAttribute => new CDbExpression($db->quoteColumnName($this->levelAttribute) . sprintf('%+d', $levelDelta))), $condition, $params);

                foreach (array($this->leftAttribute, $this->rightAttribute) as $attribute) {
                    $condition = $db->quoteColumnName($attribute) . '>=' . $left . ' AND ' . $db->quoteColumnName($attribute) . '<=' . $right;
                    $params = array();
                    if ($this->hasManyRoots) {
                        $condition .= ' AND ' . $db->quoteColumnName($this->rootAttribute) . '=' . CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount;
                        $params[CDbCriteria::PARAM_PREFIX . CDbCriteria::$paramCount++] = $owner->{$this->rootAttribute};
                    }
                    $owner->updateAll(array($attribute => new CDbExpression($db->quoteColumnName($attribute) . sprintf('%+d', $key - $left))), $condition, $params);
                }

                $this->_shiftLeftRight($right + 1, -$delta);

                if (isset($transaction)) {
                    $transaction->commit();
                }

                $this->_correctCachedOnMoveNode($key, $levelDelta);
            }
        } catch(Exception $e) {
            if (isset($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        }

        return true;
    }

    /**
     * Correct cache for {@link NestedSetBehavior::delete()} and {@link NestedSetBehavior::deleteNode()}.
     *
     * @return void
     */
    private function _correctCachedOnDelete()
    {
        $owner = $this->getOwner();
        $left = $owner->{$this->leftAttribute};
        $right = $owner->{$this->rightAttribute};
        $key = $right + 1;
        $delta = $left - $right - 1;

        foreach (self::$_cached[get_class($owner)] as $node) {
            if ($node->getIsNewRecord() || $node->getIsDeletedRecord()) {
                continue;
            }
            if ($this->hasManyRoots && $owner->{$this->rootAttribute} !== $node->{$this->rootAttribute}) {
                continue;
            }
            if ($node->{$this->leftAttribute} >= $left && $node->{$this->rightAttribute} <= $right) {
                $node->setIsDeletedRecord(true);
            } else {
                if ($node->{$this->leftAttribute} >= $key) {
                    $node->{$this->leftAttribute} += $delta;
                }
                if ($node->{$this->rightAttribute} >= $key) {
                    $node->{$this->rightAttribute} += $delta;
                }
            }
        }
    }

    /**
     * Correct cache for {@link NestedSetBehavior::_addNode()}.
     *
     * @param int $key node key
     *
     * @return void
     */
    private function _correctCachedOnAddNode($key)
    {
        $owner = $this->getOwner();
        foreach (self::$_cached[get_class($owner)] as $node) {
            if ($node->getIsNewRecord() || $node->getIsDeletedRecord()) {
                continue;
            }
            if ($this->hasManyRoots && $owner->{$this->rootAttribute} !== $node->{$this->rootAttribute}) {
                continue;
            }
            if ($owner === $node) {
                continue;
            }
            if ($node->{$this->leftAttribute} >= $key) {
                $node->{$this->leftAttribute} += 2;
            }
            if ($node->{$this->rightAttribute} >= $key) {
                $node->{$this->rightAttribute} += 2;
            }
        }
    }

    /**
     * Correct cache for {@link NestedSetBehavior::_moveNode()}.
     *
     * @param int $key        node key
     * @param int $levelDelta node level
     *
     * @return void
     */
    private function _correctCachedOnMoveNode($key, $levelDelta)
    {
        $owner = $this->getOwner();
        $left = $owner->{$this->leftAttribute};
        $right = $owner->{$this->rightAttribute};
        $delta = $right - $left + 1;

        if ($left >= $key) {
            $left += $delta;
            $right += $delta;
        }
        $delta2 = $key - $left;
        
        foreach (self::$_cached[get_class($owner)] as $node) {
            if ($node->getIsNewRecord() || $node->getIsDeletedRecord()) {
                continue;
            }
            if ($this->hasManyRoots && $owner->{$this->rootAttribute} !== $node->{$this->rootAttribute}) {
                continue;
            }
            if ($node->{$this->leftAttribute} >= $key) {
                $node->{$this->leftAttribute} += $delta;
            }
            if ($node->{$this->rightAttribute} >= $key) {
                $node->{$this->rightAttribute} += $delta;
            }
            if ($node->{$this->leftAttribute} >= $left && $node->{$this->rightAttribute} <= $right) {
                $node->{$this->levelAttribute} += $levelDelta;
            }
            if ($node->{$this->leftAttribute} >= $left && $node->{$this->leftAttribute} <= $right) {
                $node->{$this->leftAttribute} += $delta2;
            }
            if ($node->{$this->rightAttribute} >= $left && $node->{$this->rightAttribute} <= $right) {
                $node->{$this->rightAttribute} += $delta2;
            }
            if ($node->{$this->leftAttribute} >= $right + 1) {
                $node->{$this->leftAttribute} -= $delta;
            }
            if ($node->{$this->rightAttribute} >= $right + 1) {
                $node->{$this->rightAttribute} -= $delta;
            }
        }
    }

    /**
     * Correct cache for {@link NestedSetBehavior::_moveNode()}.
     * 
     * @param int $key        node key
     * @param int $levelDelta node level
     * @param int $root       root node
     * 
     * @return void
     */
    private function _correctCachedOnMoveBetweenTrees($key, $levelDelta, $root)
    {
        $owner = $this->getOwner();
        $left = $owner->{$this->leftAttribute};
        $right = $owner->{$this->rightAttribute};
        $delta = $right - $left + 1;
        $delta2 = $key - $left;
        $delta3 = $left - $right - 1;
        foreach (self::$_cached[get_class($owner)] as $node) {
            if ($node->getIsNewRecord() || $node->getIsDeletedRecord()) {
                continue;
            }
            if ($node->{$this->rootAttribute} === $root) {
                if ($node->{$this->leftAttribute} >= $key) {
                    $node->{$this->leftAttribute} += $delta;
                }
                if ($node->{$this->rightAttribute} >= $key) {
                    $node->{$this->rightAttribute} += $delta;
                }
            } else if ($node->{$this->rootAttribute} === $owner->{$this->rootAttribute}) {
                if ($node->{$this->leftAttribute} >= $left && $node->{$this->rightAttribute} <= $right) {
                    $node->{$this->leftAttribute} += $delta2;
                    $node->{$this->rightAttribute} += $delta2;
                    $node->{$this->levelAttribute} += $levelDelta;
                    $node->{$this->rootAttribute} = $root;
                } else {
                    if ($node->{$this->leftAttribute} >= $right + 1) {
                        $node->{$this->leftAttribute} += $delta3;
                    }
                    if ($node->{$this->rightAttribute} >= $right + 1) {
                        $node->{$this->rightAttribute} += $delta3;
                    }
                }
            }
        }
    }

    /**
     * Destructor.
     * 
     * @return void
     */
    public function __destruct()
    {
        unset(self::$_cached[get_class($this->getOwner())][$this->_id]);
    }
}