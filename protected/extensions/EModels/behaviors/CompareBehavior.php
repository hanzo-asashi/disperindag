<?php
/**
 * File for CompareBehavior
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@algo-rithm.com>
 * @license    http://algo-rithm.com/packages/php/license GNU Public License
 * @link       http://algo-rithm.com/packages/php/docs
 */
/**
 * CompareBehavior class provides comparing two records
 * 
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@algo-rithm.com>
 * @license    http://algo-rithm.com/packages/php/license GNU Public License
 * @link       http://algo-rithm.com/packages/php/docs
 */
class CompareBehavior extends CActiveRecordBehavior
{
    /**
     * @var array names of attributes to skip during {@link compareAttributes}
     */
    public $skipAttributes = array();

    /**
     * @var mixed boolean true to compare all has one relations or array with relation names to compare.
     * Default true.
     * @see compareHasOneRelations}
     */
    public $hasOne = true;

    /**
     *
     * @var array options to compare has-many relations.
     * @see  compareHasManyRelations
     */
    public $hasMany = array();

    /**
     *
     * @var array options to compare many-many relations.
     * @see  compareManyManyRelations
     */
    public $manyMany = array();
    
    /**
     * Compares data with other active record.
     * 
     * @param CActiveRecord $other record to compare with
     * 
     * @return bool whether objects stores identical data
     */
    public function compareWith($other, $skipAttributes = array())
    {
        if (!($other instanceof $this->owner)) {
            throw new CException('Wrong object to compare with passed');
        }
        if ($skipAttributes === null) {
            $skipAttributes = array_keys($this->owner->getAttributes());
        }
        $skipAttributes = array_merge($skipAttributes, $this->skipAttributes);
        if (!$this->compareAttributes($other, $skipAttributes)) return false;
        if (!$this->compareHasOneRelations($other, $this->hasOne)) return false;
        if (!$this->compareManyManyRelations($other, $this->manyMany)) return false;
        if (!$this->compareHasManyRelations($other, $this->hasMany)) return false;
        return true;
    }

    /**
     * Compares attributes one active record with other, skips primary key and $skipAttributes
     * 
     * @param CActiveRecord $other record to compare with
     * @param CActiveRecord $second record to compare
     * @param array $skipAttributes attribute names which should not be compared
     * 
     * @return bool whether all attributes are identical
     */
    protected function compareAttributes($other, $skipAttributes = array())
    {
        $attributes = $this->owner->attributes;
        $pk = $this->owner->getMetaData()->tableSchema->primaryKey;
        $skipAttributes[] = $pk;
        foreach ($skipAttributes as $name) {
            unset($attributes[$name]);
        }
        foreach ($attributes as $key => $value) {
            if ($other->$key !== $value) return false;
        }
        return true;
    }

    /**
     * Compares "has one" relations for specified active records.
     * Related records should have {@link CompareBehavior} attached.
     * 
     * @param CActiveRecord $other record to compare with
     * @param mixed $hasOne boolean true to compare all "has one" relations or array with relation names to compare.
     * 
     * @return bool whether related records are identical
     */
    protected function compareHasOneRelations($other, $hasOne = array())
    {
        if (!$hasOne) return true;
        if (!$other->primaryKey) throw new CException("Can not compare relations for new record");
        foreach ($this->owner->getMetaData()->relations as $key => $relation) {
            if( $relation instanceof CHasOneRelation 
                            &&
                isset($this->owner->$key) 
                            &&
                 ($hasOne === true || is_array($hasOne) && in_array($key, $hasOne))
             ) {
                //compare one-one related record
                $relThis = $this->owner->$key;
                $relOther = $other->$key;
                if ($relThis && $relOther) {
                    if (!$relThis->asa('CompareBehavior')) throw new CException("Related record {$relation->className} does not have CompareBehavior");
                    if (!$relThis->compareWith($relOther, array($relation->foreignKey))) return false;
                }
            }
        }
        return true;
    }

    /**
     * Compares "has many" relations for specified active records.
     * Related records should have {@link CompareBehavior} attached.
     * 
     * @param CActiveRecord $other record to compare with
     * @param array $hasMany array with relation names to compare.
     * 
     * @return bool whether related records are identical
     */
    protected function compareHasManyRelations($other, $hasMany)
    {
        if (!$hasMany) return true;
        if (!$other->primaryKey) throw new CException("Can not compare relations for new record");
        foreach ($this->owner->getMetaData()->relations as $key => $relation) {
            if ( $relation instanceOf CHasManyRelation 
                            &&
                isset($this->owner->$key) 
                            &&
                ($hasMany === true || is_array($hasMany) && in_array($key, $hasMany))
             ) {
                //compare one-many related records
                $foreignKey = $relation->foreignKey;
                $thisRels = $this->owner->$key;
                $otherRels = $other->$key;
                if (count($thisRels) != count($otherRels)) return false;
                if (count($thisRels) == 0) return true;
                $model = $thisRels[0];
                if (!$model->asa('CompareBehavior')) throw new CException("Related record {$relation->className} does not have CompareBehavior");
                foreach ($thisRels as $i => $thisRel) {
                    if (!$thisRel->compareWith($otherRels[$i], array($foreignKey))) return false;
                }
            }
        }
        return true;
    }

    /**
     * Compares "many many" relations for specified active records.
     * Related records should have {@link CompareBehavior} attached.
     * @param CActiveRecord $other record to compare with
     * @param array $manyMany many-many relations compare options
     * @return bool whether relations stores identical data
     * Array key should be relation name and value is an array of compare options:
     * 'class' - model class for the links table (many-many table), required.
     * 'deep' - boolean, whether to do deep compare (compares subrelated objects), default false.
     * For example
     * <pre>
     * CompareBehavior' => array(
     *     'class' => 'application.models.sandbox.CompareBehavior',
     *     'skipAttributes' => array('revision_type'),
     *     'manyMany'=>array(
     *         'features'=>array('class'=>'AppRevisionHasAppFeature', 'deep'=>true),
     *         'all_files'=>array('class'=>'AppRevisionHasAppFile'),
     *      ),
     * )
     * </pre>
     */
    protected function compareManyManyRelations($other, $manyMany)
    {
        if (!$other->primaryKey) throw new CException("Can not compare relations for new record");
        //TODO: refactor this code to relations object (not array configs) same as "copyHasManyRelations" or "copyHasOneRelations"
        //$relations = $from->getMetaData()->relations;
        $relations = $this->owner->relations();
        foreach ($manyMany as $key => $options) {
            if (!isset($options['class'])) {
                throw new CException("Required option 'class' is not specified for many-many relation");
            }
            if (!isset($options['deep'])) $options['deep'] = false;

            list($m2mTable, $m2mThisField, $m2mForeignField) =
                self::parseManyMany($relations[$key]);

            $linkModel = call_user_func(array($options['class'], 'model'));
            $links = $linkModel->findAllByAttributes(array($m2mThisField => $this->owner->primaryKey));
            $otherLinks = $linkModel->findAllByAttributes(array($m2mThisField => $other->primaryKey));
            if (count($links) != count($otherLinks)) return false;
            //if (!$linkModel->asa('CompareBehavior')) throw new CException("Related record {$options['class']} does not have CompareBehavior"); 
            foreach ($links as $i => $link) {
                $otherLink = $otherLinks[$i];
                if ($linkModel->asa('CompareBehavior')) {
                    if (!$link->compareAttributes($otherLink)) return false;    
                } else {
                    if ($link->$m2mForeignField !== $otherLink->$m2mForeignField) return false; 
                }
                if ($options['deep']) {
                    //deep compare
                    $related = $this->owner->$m2mForeignField;
                    $otherRelated = $other->$m2mForeignField;
                    if (count($related) != count($otherRelated)) return false;
                    foreach ($related as $j => $rel) {
                        if (!$rel->asa('CompareBehavior')) throw new CException("Related record {$relations[$key][1]} does not have CompareBehavior");
                        if (!$rel->compareWith($otherRelated[$j])) return false;
                    }
                }
            }
        }
        return true;
    }
    
    /**
     * Parses many-many relation and returns parts
     * 
     * @param array $relation relation configuration
     * 
     * @return array relation parts
     */
    private static function parseManyMany($relation)
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