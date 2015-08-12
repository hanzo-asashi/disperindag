<?php

/**
 * File for AdjacencyTreeBehavior.
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * AdjacencyTreeBehavior is the AR behavior class which
 * allows to organize adjacency tree int database table.
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
class AdjacencyTreeBehavior extends CActiveRecordBehavior
{
    /**
     * Column name for parent/child relationships.
     *
     * @var string
     */
    public $parentIdColumn = 'parent_id';

    /**
     * Column name for tree nested level.
     *
     * @var string
     */
    public $levelColumn = 'level';

    /**
     * Column name for tree item is leaf flag.
     *
     * @var string
     */
    public $isLeafColumn = 'is_leaf';

    /**
     * Attaches dynamic relations.
     * 
     * @param CActiveRecord $owner owner
     * 
     * @see CBehavior::attach()
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $ownerClassName = get_class($owner);
        $metaData = $owner->getMetaData();
        $metaData->addRelation('parent', array(
            CActiveRecord::BELONGS_TO, $ownerClassName, $this->parentIdColumn,
        ));
    }

    /**
     * (non-PHPdoc).
     * 
     * @param CEvent $event handled event
     * 
     * @see CActiveRecordBehavior::beforeSave()
     */
    public function beforeSave($event)
    {
        $owner = $this->getOwner();
        if ($owner->{$this->parentIdColumn} <= 0) {
            $owner->{$this->parentIdColumn} = null;
        }
        if ($parent = $owner->parent) {
            $owner->{$this->levelColumn} = $parent->{$this->levelColumn} + 1;
        } else {
            $owner->{$this->levelColumn} = 0;
        }
        if ($owner->getIsNewRecord()) {
            $owner->{$this->isLeafColumn} = 1;
        } else {
            $criteria = new CDbCriteria();
            $criteria->compare('t.parent_id', $owner->id);
            $owner->{$this->isLeafColumn} = $owner->exists($criteria) ? 0 : 1;
        }
    }

    /**
     * Change is_leaf column value if child nodes assigned.
     * 
     * @param CEvent $event handled event
     * 
     * @see CActiveRecordBehavior::afterSave()
     */
    public function afterSave($event)
    {
        $owner = $this->getOwner();
        if ($parentId = $owner->{$this->parentIdColumn}) {
            $parent = $owner->findByPk($parentId);
            $parent->{$this->isLeafColumn} = 0;
            $parent->save(false, array($this->isLeafColumn));
        }
    }

    /**
     * Change is_leaf column value if parent node hasn't any child.
     * 
     * @param CEvent $event handled event
     * 
     * @see CActiveRecordBehavior::afterDelete()
     */
    public function afterDelete($event)
    {
        $owner = $this->getOwner();
        if ($parentId = $owner->{$this->parentIdColumn}) {
            $criteria = new CDbCriteria();
            $criteria->compare($this->parentIdColumn, $owner->parent_id);
            if (!$parent = $owner->findByPk($parentId)) {
                return;
            }
            if (!$childrenCount = $owner->count($criteria)) {
                $parent->{$this->isLeafColumn} = 1;
                $parent->save(false, array($this->isLeafColumn));
            }
        }
    }

    /**
     * Removes all children recursively before deleting record.
     *
     * @param CEvent $event handled event
     *
     * @see CActiveRecordBehavior::beforeDelete()
     */
    public function beforeDelete($event)
    {
        $owner = $this->getOwner();
        $parentId = $owner->{$this->parentIdColumn};
        $criteria = new CDbCriteria();
        $criteria->compare($this->parentIdColumn, $owner->id);
        foreach ($owner->findAll($criteria) as $child) {
            $child->delete();
        }
    }
}
