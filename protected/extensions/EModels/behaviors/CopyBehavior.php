<?php
/**
 * File for CopyBehavior
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@algo-rithm.com>
 * @license    http://algo-rithm.com/packages/php/license GNU Public License
 * @link       http://algo-rithm.com/packages/php/docs
 */
/**
 * CopyBehavior class allows to copy active record with related data
 * 
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@algo-rithm.com>
 * @license    http://algo-rithm.com/packages/php/license GNU Public License
 * @link       http://algo-rithm.com/packages/php/docs
 */
class CopyBehavior extends CActiveRecordBehavior
{
    /**
     * Attributes names to force copy
     * @var array
     */
    public $forceCopyAttributes = array();
    
    /**
     * @var array names of attributes to skip during {@link copyAttributes}
     */
    public $skipAttributes = array();

    /**
     * @var mixed boolean true to copy all has one relations or array with relation names to copy.
     * Default true.
     * @see copyHasOneRelations}
     */
    public $hasOne = true;

    /**
     *
     * @var array options to copy has-many relations.
     * @see  copyHasManyRelations
     */
    public $hasMany = array();

    /**
     *
     * @var array options to copy many-many relations.
     * @see  copyManyManyRelations
     */
    public $manyMany = array();

    /**
     * Copy data from another active record.
     * 
     * @param CActiveRecord $from           record to copy from
     * @param array         $skipAttributes attribute names for ignoring
     * @param array         $values         additional values
     * 
     * @return boolean whether operation success
     */
    public function copyFrom($from, $skipAttributes = array(), $values = array())
    {
        $owner = $this->getOwner();
        if (!($from instanceof $owner)) {
            throw new CException('Wrong object to copy from passed');
        }
        if ($skipAttributes === null) {
            $skipAttributes = array_keys($owner->getAttributes());
        }
        $skipAttributes = array_merge($skipAttributes, $this->skipAttributes);
        $skipAttributes = array_diff($skipAttributes, $this->forceCopyAttributes);

        self::copyAttributes($from, $owner, $skipAttributes, $this->forceCopyAttributes);
        $owner->setAttributes($values);
        if (!$owner->save(false)) {
            throw new CException("Can not save record after copy. " . CHtml::errorSummary($owner) );
        }

        self::copyHasOneRelations($from, $owner, $this->hasOne);
        self::copyManyManyRelations($from, $owner, $this->manyMany);
        self::copyHasManyRelations($from, $owner, $this->hasMany);

        return true;
    }

    /**
     * Copies attributes from one active record to another, skips primary key.
     * 
     * @param CActiveRecord $from                record to copy from
     * @param CActiveRecord $to                  record to copy to
     * @param array         $skipAttributes      attribute names which should not be copied
     * @param array         $forceCopyAttributes attribute names which should be force copied
     * 
     * @return void
     */
    protected static function copyAttributes($from, $to, $skipAttributes = array(), $forceCopyAttributes = array())
    {
        $attributes = $from->getAttributes();
        $pk = $to->getMetaData()->tableSchema->primaryKey;
        if (!is_array($pk)) {
           $skipAttributes[] = $pk;
        } else {
            $skipAttributes = array_merge($skipAttributes, $pk);
        }
        $skipAttributes = array_diff($skipAttributes, $forceCopyAttributes);
        foreach ($skipAttributes as $attribute) {
            unset($attributes[$attribute]);
        }
        $to->setAttributes($attributes, true);
    }

    /**
     * Copies "has one" relations for specified active records.
     * Related records should have {@link CopyBehavior} attached.
     * 
     * @param CActiveRecord $from record to copy from
     * @param CActiveRecord $to record to copy to
     * @param mixed $hasOne boolean true to copy all "has one" relations or array with relation names to copy.
     * 
     * @return void
     */
    static function copyHasOneRelations($from, $to, $hasOne = array())
    {
        if (!$hasOne) return;

        if (!$to->primaryKey) {
            throw new CException("Can not copy relations for new record");
        }

        foreach($from->getMetaData()->relations as $key => $relation) {
            if( $relation instanceof CHasOneRelation &&
                isset($from->$key) &&
                (
                  $hasOne === true ||
                  (
                    is_array($hasOne) &&
                    in_array($key, $hasOne)
                  )
                )
             ) {
                //copy one-one related record
                if(!isset($to->$key)) $to->$key = new $relation->className;
                if ($to->$key->asa('CopyBehavior')) {
                    $to->$key->{$relation->foreignKey} = $to->primaryKey;
                    $to->$key->copyFrom($from->$key, array($relation->foreignKey));
                } else {
                    throw new CException(
                        "Related {$relation->className} (by fk {$relation->foreignKey}) does not have CopyBehavior"
                    );
                    //self::copyAttributes($from->$key, $this->owner->$key, array($relation[2]));
                }
            }
        }
    }

    /**
     * Copies "has many" relations for specified active records.
     * Related records should have {@link CopyBehavior} attached.
     * 
     * @param CActiveRecord $from record to copy from
     * @param CActiveRecord $to record to copy to
     * @param arrray $hasMany array with relation names to copy.
     * 
     * @return void
     */
    protected static function copyHasManyRelations($from, $to, $hasMany)
    {
        if (!$hasMany) return;

        if (!$to->primaryKey) {
            throw new CException("Can not copy relations for new record");
        }

        foreach ($from->getMetaData()->relations as $key => $relation) {
            if ($relation instanceOf CHasManyRelation &&
                isset($from->$key) &&
                (
                  $hasMany === true ||
                  (
                    is_array($hasMany) &&
                    in_array($key, $hasMany)
                  )
                )
             ) {
                //copy one-many related records
                $model = new $relation->className;
                if ($model->asa('CopyBehavior')) {
                    $oldRecords = $to->$key;
                    foreach ($oldRecords as $oldRecord) {
                        $oldRecord->delete();
                    }
                    $records = $from->$key;
                    foreach ($records as $record) {
                        $newRecord = new $relation->className;
                        $primaryAttributes = self::_resolvePrimaryAttributes($record);
                        if (count($primaryAttributes) > 1) {
                            $newRecord->setAttributes($primaryAttributes);
                        }
                        $newRecord->{$relation->foreignKey} = $to->getPrimaryKey();
                        $skipAttributes = array_keys($primaryAttributes);
                        $skipAttributes[] = $relation->foreignKey;
                        $newRecord->copyFrom($record, $skipAttributes);
                    }
                } else {
                    throw new CException(
                        "Related {$relation->className} (by {$relation->foreignKey}) does not have CopyBehavior"
                    );
                }
            }
        }
    }

    /**
     * Copies "many many" relations for specified active records.
     * Related records should have {@link CopyBehavior} attached.
     * 
     * @param CActiveRecord $from record to copy from
     * @param CActiveRecord $to record to copy to
     * @param array $manyMany many-many relations copy options
     * Array key should be relation name and value is an array of copy options:
     * 'class' - model class for the links table (many-many table), required.
     * 'deep' - boolean, whether to do deep copy (create copies of related objects), default false.
     * 'cleanRelated' - boolean, whether to delete records from the related table
     * if after copy there are no references to these records from the links table, default false.
     * For example
     * <pre>
     * CopyBehavior' => array(
     *     'class' => 'application.models.sandbox.CopyBehavior',
     *     'skipAttributes' => array('revision_type'),
     *     'manyMany'=>array(
     *         'features'=>array('class'=>'AppRevisionHasAppFeature', 'deep'=>true, 'cleanRelated'=>true),
     *         'all_files'=>array('class'=>'AppRevisionHasAppFile'),
     *      ),
     * )
     * </pre>
     * 
     * @return void
     */
    protected static function copyManyManyRelations($from, $to, $manyMany)
    {
        if (!$to->primaryKey) {
            throw new CException("Can not copy relations for new record");
        }

        //TODO: refactor this code to relations object (not array configs) same as "copyHasManyRelations" or "copyHasOneRelations"
        //$relations = $from->getMetaData()->relations;
        $relations = $from->relations();
        foreach ($manyMany as $key => $options) {
            if (!isset($options['class'])) {
                throw new CException("Required option 'class' is not specified for many-many relation");
            }
            if (!isset($options['deep'])) $options['deep'] = false;
            if (!isset($options['cleanRelated'])) $options['cleanRelated'] = false;

            list($m2mTable, $m2mThisField, $m2mForeignField) =
                self::parseManyMany($relations[$key]);

            //delete current links with related data
            $linkModel = call_user_func(array($options['class'], 'model'));
            $linkModel->deleteAllByAttributes(array($m2mThisField => $to->primaryKey));

            //copy links (and data in the case of deep copy)
            $links = $linkModel->findAllByAttributes(array($m2mThisField => $from->primaryKey));
            foreach ($links as $link) {
                $newLink = new $options['class'];
                self::copyAttributes($link, $newLink);
                $newLink->$m2mThisField =  $to->primaryKey;
                if (!$options['deep']) {
                    $newLink->$m2mForeignField = $link->$m2mForeignField;
                } else {
                    //deep copy
                    $foreignClass = $relations[$key]['1'];
                    $foreignNew = new $foreignClass;
                    $foreignOld = call_user_func(array($foreignClass, 'model'))
                        ->findByPk($link->$m2mForeignField);
                    if ($foreignNew->asa('CopyBehavior')) {
                        $foreignNew->copyFrom($foreignOld);
                    } else {
                        throw new CException("Many-many foreign record $foreignClass does not have CopyBehavior");
                    }
                    $newLink->$m2mForeignField = $foreignNew->primaryKey;
                }
                if (!$newLink->save()) {
                    throw new CException("Can not save many-many link, $m2mTable: " .
                        CHtml::errorSummary($newLink));
                }
            }

            //clean related table if necessary
            if ($options['cleanRelated']) {
                self::cleanManyManyRelation($relations[$key]);
            }
        }
    }
    
    /**
     * Resolves primary key attributes (name => value)
     * 
     * @param CActiveRecord $record record to resolve primary key
     * 
     * @return array primary key attributes
     */
    private static function _resolvePrimaryAttributes($record)
    {
        $key = $record->getMetaData()->tableSchema->primaryKey;
        $primaryAttributes = $record->getPrimaryKey();
        if (is_array($key)) {
            return $primaryAttributes;
        } else {
            return array($key => $primaryAttributes);
        }
    }

    /**
     * Clears many-many relations
     * 
     * @param array $relation relation config
     * 
     * @return void
     */
    static function cleanManyManyRelation($relation)
    {
        $foreignModel =  call_user_func(array($relation['1'], 'model'));
        $foreignModelKey = CActiveRecord::model($relation['1'])->tableSchema->primaryKey;
        list($m2mTable, $m2mThisField, $m2mForeignField) =
                self::parseManyMany($relation);

        $condition =
            "$foreignModelKey not in ( select $m2mForeignField" .
            " from $m2mTable ) ";
        //deleteAll does not invokes afterDelete for records
        //$data = $model->deleteAll($condition);

        //call delete for each record to be sure beforeDelete / afterDelete is invoked
        $data = $foreignModel->findAll($condition);
        foreach ($data as $record) {
            $record->delete();
        }
    }

    static function parseManyMany($relation)
    {
        if ($relation['0'] != CActiveRecord::MANY_MANY) {
            throw new CException("Relation {$relation['1']} is not many-many relation");
        }
        if (preg_match('/^(.+)\((.+)\s*,\s*(.+)\)$/s', $relation['2'], $parts)) {
            //$m2mTable, $m2mThisField, $m2mForeignField
            return array($parts[1], $parts[2], $parts[3]);
        } else {
            throw new CException("Can not parse many-many relation: {$relation['2']}");
        }
    }


}