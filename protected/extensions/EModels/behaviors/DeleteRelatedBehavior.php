<?php
/**
 * DeleteRelatedBehavior
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Alexandr Vinogradov <winogradow@jviba.com>
 * @author     Dmitri Cherepovski <cherep@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * DeleteRelatedBehavior can delete both referencing and referenced active records, related
 * to its owner active record. To define, which records are related to the current one,
 * YII native relations properties can be used.
 *
 * For example, if you have getter
 * <pre>
 * public function getMedia() {...}
 * </pre>
 * you can define it in the 'relations' property as
 * <pre>
 *  array(
 *      'name' => 'media', //name of the property
 *      'field' => 'media_id',   // name of the field used as a foreign key
 *      'type' => CActiveRecord::HAS_ONE // relation type
 *  )
 * </pre>
 * much alike to the relation configuration
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Alexandr Vinogradov <winogradow@jviba.com>
 * @author     Dmitri Cherepovski <cherep@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 * @link       http://jviba.com/packages/php/docs
 */
class DeleteRelatedBehavior extends CActiveRecordBehavior
{
    /**
     * Relations to be deleted
     * example: array(
     *  array(
     *      //the relation
     *      'name' => 'photo',
     *  ),
     *  array(
     *      //the property
     *      'name' => 'media',
     *      'field' => 'media_id',   // name of the field used as a foreign key
     *      'type' => CActiveRecord::HAS_ONE, // HAS_ONE or BELONGS_TO
     *      'mustPreventDeleteOfUsed' => false //if this flag is TRUE, deletion only happens when no
     *          other record in the same table references the same referenced record
     *  )
     * )
     * @var array
     */
    public $relations;

    /**
     * Checks if any other DB record of the same type as owner uses the given related record
     *
     * @param string $attr  Name of DB attribute
     * @param mixed  $value Value of DB attribute
     *
     * @return boolean Check result
     */
    private function _isRelatedRecordUsedByOthers($attr, $value)
    {
        $criteria = new CDbCriteria();
        $criteria->compare($attr, $value);
        return $this->getOwner()->exists($criteria);
    }

    /**
     * Deletes the HAS_ONE related record
     *
     * @param string $propertyName Name of the related record property
     *
     * @return void
     */
    protected function deleteHasOne($propertyName)
    {
        if ($model = $this->getOwner()->$propertyName) {
            $model->delete();
        }
    }

    /**
     * Deletes the HAS_MANY related records
     *
     * @param string $propertyName Name of the related record property
     *
     * @return void
     */
    protected function deleteHasMany($propertyName)
    {
        foreach ($this->getOwner()->$propertyName as $model) {
            $model->delete();
        }
    }

    /**
     * Deletes the BELONGS_TO related record
     *
     * @param string $propertyName            Name of the related record property
     * @param string $foreignKey              Name of the foreign key attribute, which links to
     * the referenced table
     * @param string $mustPreventDeleteOfUsed If this flag is TRUE, deletion only happens when no
     * other record in the same table references the same referenced record
     *
     * @return void
     * @throws CException If the referenced table has composite primary key
     */
    protected function deleteBelongsTo($propertyName, $foreignKey, $mustPreventDeleteOfUsed)
    {
        if (($model = $this->getOwner()->$propertyName) && ($model instanceof CActiveRecord)) {
            $relatedPrimaryKey = $model->getPrimaryKey();
            if (is_array($relatedPrimaryKey)) {
                throw new CException(
                    'DeleteRelatedBehavior',
                    'Can not delete related models with composite primary keys. Not supported yet.'
                );
            }
            if (! $mustPreventDeleteOfUsed || ! $this->_isRelatedRecordUsedByOthers($foreignKey, $relatedPrimaryKey)) {
                $model->delete();
            }
        }
    }

    /**
     * Resolves a single item of the 'relations' behavior parameter
     *
     * @param mixed $relationInfo The relation configuration item
     *
     * @return array (relationName, relationForeignKey, relationType)
     * @throws CException If the relation configuration item is invalid
     */
    protected function resolveRelationInfo($relationInfo)
    {
        if (is_array($relationInfo)) {
            return array(
                $relationInfo['name'],
                isset($relationInfo['field']) ? $relationInfo['field'] : null,
                isset($relationInfo['type']) ? $relationInfo['type'] : null,
                isset($relationInfo['mustPreventDeleteOfUsed']) ? $relationInfo['mustPreventDeleteOfUsed'] : true,
            );
        } elseif (is_string($relationInfo)) {
            return array(
                $relationInfo,
                null,
                null,
                isset($relationInfo['mustPreventDeleteOfUsed']) ? $relationInfo['mustPreventDeleteOfUsed'] : true,
            );
        }
        throw new CException('DeleteRelatedBehavior', 'Wrong relation info in behavior configuration');
    }

    /**
     * Deletes the referenced related data
     *
     * @param CEvent $event handled event
     *
     * @return void
     * @see CActiveRecordBehavior::afterDelete()
     */
    public function beforeDelete($event)
    {
        $owner = $this->getOwner();
        $ownerRelations = $owner->getMetaData()->relations;
        foreach ($this->relations as $relationInfo) {
            list(
                $relationName,
                $foreignKey,
                $propertyRelationType,
                $mustPreventDeleteOfUsed
            ) = $this->resolveRelationInfo($relationInfo);
            if (isset($ownerRelations[$relationName])) {
                //relation
                $relation = $ownerRelations[$relationName];
                if ($relation instanceof CHasOneRelation) {
                    $this->deleteHasOne($relationName);
                } elseif ($relation instanceof CHasManyRelation) {
                    $this->deleteHasMany($relationName);
                } elseif ($relation instanceof CManyManyRelation) {
                    throw new CException(
                        Yii::t(
                            'DeleteRelatedBehavior',
                            'Can not delete CManyManyRelation, not supported yet.'
                        )
                    );
                }
            } elseif (($propertyRelationType == CActiveRecord::HAS_ONE)
                && ($owner->$relationName || $owner->canGetProperty($relationName))
            ) {
                //property
                if (! is_string($foreignKey)) {
                    throw new CException(
                        Yii::t(
                            'DeleteRelatedBehavior',
                            'Can not delete through property {propertyName}, no foreign key given in the configuration.',
                            array('{propertyName}' => $relationName)
                        )
                    );
                }
                $this->deleteHasOneProperty($relationName, $foreignKey);
            }
        }
    }

    /**
     * Deletes the referencing related records
     *
     * @param CEvent $event handled event
     *
     * @return void
     * @throws CException
     */
    public function afterDelete($event)
    {
        $owner = $this->getOwner();
        $ownerRelations = $owner->getMetaData()->relations;
        foreach ($this->relations as $relationInfo) {
            list(
                $relationName,
                $foreignKey,
                $propertyRelationType,
                $mustPreventDeleteOfUsed
            ) = $this->resolveRelationInfo($relationInfo);
            if (isset($ownerRelations[$relationName])) {
                //relation
                $relation = $ownerRelations[$relationName];
                if ($relation instanceof CBelongsToRelation) {
                    $this->deleteBelongsTo(
                        $relationName,
                        $relation->foreignKey,
                        $mustPreventDeleteOfUsed
                    );
                }
            } elseif (($propertyRelationType == CActiveRecord::BELONGS_TO)
                && ($owner->$relationName || $owner->canGetProperty($relationName))
            ) {
                //property
                if (! is_string($foreignKey)) {
                    throw new CException(
                        'DeleteRelatedBehavior',
                        'Can not delete through property {propertyName}, no foreign key given in the configuration.',
                        array('{propertyName}' => $relationName)
                    );
                }
                $this->deleteBelongsTo($relationName, $foreignKey, $mustPreventDeleteOfUsed);
            }
        }
    }
}