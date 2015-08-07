<?php
/**
 * CHasOneBehavior class
 *
 * PHP version 5
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
/**
 * CHasOneBehavior is the behavior which provides automatic
 * creating related records after finding record
 * 
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
class CHasOneBehavior extends CActiveRecordBehavior
{
    const RELATION_REQUIRED_PARAMS_COUNT = 3;
    
    /**
     * Whether required to create related HAS_ONE record if it is not exists
     * @var bool
     */
    public $createRelated = true;
    
    /**
     * Whether required to insert related record if not exists
     * @var bool
     */
    public $insertIfNotExists = false;

    /**
     * Whether required to save automatically related HAS_ONE record
     * @var bool
     */
    public $saveRelated;

    /**
     * Array of relation names to process creating and saving related
     * @var array relation names to skip
     */
    public $relations = array();
    
    /**
     * Initialized properties defaults on attaching
     *
     * @param CActiveRecord $owner behavior owner
     * 
     * @return void
     * @see CBehavior::attach()
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if (!isset($this->saveRelated)) {
            $this->saveRelated = $owner->isNewRecord;
        }
    }
    
    /**
     * Returns has one relation behavior configuration
     * 
     * @param string $relation relation name
     * 
     * @return array relation's behavior configuration
     */
    private function _getRelationConfig($relation)
    {
        $base = array(
            'createRelated' => $this->createRelated,
            'saveRelated' => $this->saveRelated,
            'insertIfNotExists' => $this->insertIfNotExists,
        );
        $additional = isset($this->relations[$relation]) 
                    ? $this->relations[$relation]
                    : array();
        return CMap::mergeArray($base, $additional);
    }
    
    /**
     * Provides creating related HAS_ONE records in memory
     * 
     * @param CEvent $event event object
     * 
     * @return void
     * @see CActiveRecordBehavior::afterFind()
     * @throws Exception
     */
    public function afterFind($event)
    {
        $owner = $this->getOwner();
        foreach ($owner->relations() as $key => $relation) {
            $isHasOne = $relation['0'] == CActiveRecord::HAS_ONE;
            if (!$isHasOne) {
                continue;
            }
            if (isset($this->relations[$key]) || in_array($key, $this->relations)) {
                $config = $this->_getRelationConfig($key);
                if ($config['createRelated']) {
                    $criteria = new CDbCriteria();
                    $parameters = array_slice($relation, self::RELATION_REQUIRED_PARAMS_COUNT);
                    foreach ($parameters as $name => $value) {
                        $name = $name == 'on' ? 'condition' : $name;
                        $criteria->$name = $value;
                    }
                    $criteria->compare($relation[2], $owner->primaryKey);
                    $model = CActiveRecord::model($relation[1]);
                    if (!$model->exists($criteria)) {
                        $related = new $relation[1];
                        $related->$relation[2] = $owner->primaryKey;
                        if ($config['insertIfNotExists']) {
                            if (!$related->save(false)) {
                                $message = 'Can not save related record. ' . CHtml::errorSummary($related);
                                throw new CException($message);
                            }
                        }
                        $this->owner->addRelatedRecord($key, $related, false);
                    }
                }
            }
        }
        parent::afterFind($event);
    }
    
    /**
     * Provides saving related HAS_ONE records
     * 
     * @param CEvent $event event object
     * 
     * @return void 
     * @see CActiveRecordBehavior::afterSave()
     * @throws Exception
     */
    public function afterSave($event)
    {
        $owner = $this->getOwner();
        foreach ($owner->relations() as $key => $relation) {
            $isHasOne = $relation['0'] == CActiveRecord::HAS_ONE;
            if (!$isHasOne) {
                continue;
            }
            if (isset($this->relations[$key]) || in_array($key, $this->relations)) {
                $config = $this->_getRelationConfig($key);
                $owner->$key->$relation[2] = $owner->primaryKey;
                if ($config['createRelated'] && !$owner->$key) {
                    $related = new $relation[1];
                    $related->$relation[2] = $owner->primaryKey;
                } else {
                    $related = $owner->$key;
                }
                if ($config['saveRelated'] && !$related->save()) {
                    $message = 'Can not save related record. ' . CHtml::errorSummary($related);
                    throw new CException($message);
                }
            }
        }
        parent::afterSave($event);
    }
}