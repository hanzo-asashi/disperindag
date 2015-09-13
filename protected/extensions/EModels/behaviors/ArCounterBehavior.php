<?php

/**
 * File for ArCounterBehavior.
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * ArCounterBehavior is the AR behavior class which
 * allows to increase and get model counters values.
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
class ArCounterBehavior extends CActiveRecordBehavior
{
    /**
     * Counters configuration.
     *
     * @var array
     */
    public $counters = array();

    /**
     * Default counter name.
     *
     * @var string
     */
    public $defaultCounter;

    /**
     * Specifies attribute suffix.
     *
     * @var string
     */
    public $attributeSuffix = '_counter';

    /**
     * (non-PHPdoc).
     * 
     * @see CBehavior::attach()
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if (!isset($this->defaultCounter)) {
            $this->defaultCounter = $this->counters[0];
        }
    }

    /**
     * Returns counter attribute name.
     * 
     * @param string $name counter name, or null if default counter or single
     * 
     * @return string resolved counter attribute name
     *
     * @throws Exception when undefined counter used
     */
    public function resolveCounterAttribute($name = null)
    {
        $name = $name === null ? $this->defaultCounter : $name;
        if (in_array($name, $this->counters)) {
            return $name.$this->attributeSuffix;
        } else {
            throw new CException('Unknown counter usage: "'.$name.'"');
        }
    }

    /**
     * Returns counter value.
     * 
     * @param string $name counter name, or null if default counter or single
     * 
     * @return mixed counter value
     */
    public function getCounter($name = null)
    {
        $attribute = $this->resolveCounterAttribute($name);

        return $this->getOwner()->$attribute;
    }

    /**
     * Updates counter value.
     * 
     * @param int    $value counter value
     * @param string $name  counter name. Null means default counter should been used.
     * 
     * @return bool whether counter value updates successfully
     */
    public function setCounter($value, $name = null)
    {
        $attribute = $this->resolveCounterAttribute($name);
        $owner = $this->getOwner();
        $owner->$attribute = $value;
        $builder = $owner->getCommandBuilder();
        $data = array($attribute => $value);
        $criteria = new CDbCriteria();
        $criteria->compare($owner->getTableSchema()->primaryKey, $owner->getPrimaryKey());
        $command = $builder->createUpdateCommand($owner->tableName(), $data, $criteria);

        return (bool) $command->execute();
    }

    /**
     * Increase counter value.
     * 
     * @param string $name counter name, or null if default counter or single
     * 
     * @return bool whether operation success
     */
    public function incCounter($name = null)
    {
        $attribute = $this->resolveCounterAttribute($name);

        return $this->setCounter($this->getCounter($name) + 1, $name);
    }

    /**
     * Decrease counter value.
     * 
     * @param string $name counter name, or null if default counter or single
     * 
     * @return bool whether operation success
     */
    public function decCounter($name = null)
    {
        $attribute = $this->resolveCounterAttribute($name);

        return $this->setCounter($this->getCounter($name) - 1, $name);
    }
}
