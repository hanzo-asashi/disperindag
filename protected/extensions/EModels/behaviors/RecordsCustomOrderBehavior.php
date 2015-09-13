<?php

/**
 * File for RecordsCustomOrderBehavior.
 *
 * @category   Packages
 *
 * @author     Alexander Melyakov <melyakov@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * RecordsCustomOrderBehavior is the AR behavior class which
 * allows to sort the records in order.
 *
 * @category   Packages
 *
 * @author     Alexander Melyakov <melyakov@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
class RecordsCustomOrderBehavior extends CActiveRecordBehavior
{
    /**
     * Order attribute name.
     *
     * @var string
     */
    public $orderAttribute = 'index';

    /**
     * If needed re-index order attribute.
     *
     * @var bool
     */
    public $reorderAfterDelete = true;

    /**
     * Database base criteria used for ordering records in table.
     *
     * @var CDbCriteria|array
     */
    public $criteria = array();

    /**
     * Runtime base criteria parameters' expressions.
     *
     * @var array
     */
    public $params = array();

    /**
     * Base criteria cache.
     *
     * @var CDbCriteria
     */
    private $_baseCriteria;

    /**
     * Returns initialized base records search criteria.
     * 
     * @return CDbCriteria base criteria
     */
    protected function getBaseCriteria()
    {
        if (!isset($this->_baseCriteria)) {
            $this->_baseCriteria = $this->criteria instanceof CDbCriteria
                                 ? $this->criteria
                                 : new CDbCriteria($this->criteria);
            if (!empty($this->params)) {
                $owner = $this->getOwner();
                foreach ($this->params as $name => $expression) {
                    $this->_baseCriteria->params[$name] = $this->evaluateExpression(
                        $expression, array('owner' => $owner)
                    );
                }
            }
        }

        return $this->_baseCriteria;
    }

    /**
     * Responds to {@link CModel::onBeforeSave} event.
     * Sets the values of the creation or modified attributes as configured.
     *
     * @param CModelEvent $event event parameter
     */
    public function beforeSave($event)
    {
        $owner = $this->getOwner();
        if ($owner->getIsNewRecord() && ($this->orderAttribute !== null)) {
            $owner->{$this->orderAttribute} = $this->getMaxIndex() + 1;
        }
    }

    /**
     * Responds to {@link CActiveRecord::onAfterDelete} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     *
     * @param CEvent $event event parameter
     */
    public function afterDelete($event)
    {
        if ($this->reorderAfterDelete) {
            $attribute = $this->orderAttribute;
            $owner = $this->getOwner();
            $index = $owner->{$attribute};
            $criteria = clone $this->getBaseCriteria();
            $criteria->addCondition("`{$attribute}` > :index");
            $criteria->params[':index'] = $index;
            $command = $owner->getDbConnection()->getCommandBuilder()->createUpdateCommand(
                $owner->tableName(),
                array($attribute => new CDbExpression("`{$attribute}` - 1")),
                $criteria
            );
            $command->execute($criteria->params);
        }
    }

    /**
     * Gets max order index.
     *
     * @return int max index
     */
    protected function getMaxIndex()
    {
        $owner = $this->getOwner();
        $criteria = clone $this->getBaseCriteria();
        $criteria->select = "MAX(`{$this->orderAttribute}`)";
        $command = $owner->getDbConnection()->getCommandBuilder()->createFindCommand(
            $owner->tableName(),
            $criteria
        );

        return $command->queryScalar();
    }

    /**
     * Gets min order index.
     *
     * @return int min index
     */
    protected function getMinIndex()
    {
        $owner = $this->getOwner();
        $criteria = clone $this->getBaseCriteria();
        $criteria->select = "MIN(`{$this->orderAttribute}`)";
        $command = $owner->getDbConnection()->getCommandBuilder()->createFindCommand(
            $owner->tableName(),
            $criteria
        );

        return $command->queryScalar();
    }

    /**
     * Gets previous index for owner record.
     *
     * @return int previous index
     */
    protected function getPrevIndex()
    {
        $owner = $this->getOwner();
        $index = $owner->{$this->orderAttribute};
        $criteria = clone $this->getBaseCriteria();
        $criteria->select = "MAX(`{$this->orderAttribute}`)";
        $criteria->addCondition("t.{$this->orderAttribute} < :index");
        $criteria->params[':index'] = $index;
        $command = $owner->getDbConnection()->getCommandBuilder()->createFindCommand(
            $owner->tableName(),
            $criteria
        );

        return $command->queryScalar($criteria->params);
    }

    /**
     * Gets next index for owner record.
     *
     * @return int next index
     */
    protected function getNextIndex()
    {
        $owner = $this->getOwner();
        $index = $owner->{$this->orderAttribute};
        $criteria = clone $this->getBaseCriteria();
        $criteria->select = "MIN(`{$this->orderAttribute}`)";
        $criteria->addCondition("t.{$this->orderAttribute} > :index");
        $criteria->params[':index'] = $index;
        $command = $owner->getDbConnection()->getCommandBuilder()->createFindCommand(
            $owner->tableName(),
            $criteria
        );

        return $command->queryScalar($criteria->params);
    }

    /**
     * Moves the record to the top of one position.
     *
     * @return bool
     */
    public function orderUp()
    {
        $attribute = $this->orderAttribute;
        $owner = $this->getOwner();
        $index = $owner->{$attribute};
        if ($index != $this->getMinIndex()) {
            $prevIndex = $this->getPrevIndex();
            $prev = $this->findByIndex($prevIndex);
            $owner->{$attribute} = $prevIndex;
            $prev->{$attribute} = $index;

            return $owner->save(true, array($attribute)) && $prev->save(true, array($attribute));
        }

        return true;
    }

    /**
     * Moves the record to the bottom of one position.
     *
     * @return bool
     */
    public function orderDown()
    {
        $attribute = $this->orderAttribute;
        $owner = $this->getOwner();
        $index = $owner->{$attribute};
        if ($index != $this->getMaxIndex()) {
            $nextIndex = $this->getNextIndex();
            $next = $this->findByIndex($nextIndex);
            $owner->{$attribute} = $nextIndex;
            $next->{$attribute} = $index;

            return $owner->save(true, array($attribute)) && $next->save(true, array($attribute));
        }

        return true;
    }

    /**
     * Gets record by order index.
     *
     * @param $index index
     *
     * @return CActiveRecord
     */
    public function findByIndex($index)
    {
        $criteria = clone $this->getBaseCriteria();
        $criteria->compare('t.'.$this->orderAttribute, $index);

        return CActiveRecord::model(get_class($this->getOwner()))->find($criteria);
    }

    /**
     * Checks if record can be up.
     *
     * @return bool
     */
    public function getCanOrderUp()
    {
        return $this->getOwner()->{$this->orderAttribute} != $this->getMinIndex();
    }

    /**
     * Checks if record can be down.
     *
     * @return bool
     */
    public function getCanOrderDown()
    {
        return $this->getOwner()->{$this->orderAttribute} != $this->getMaxIndex();
    }
}
