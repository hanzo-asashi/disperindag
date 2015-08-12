<?php

/**
 * TranslationBehavior class.
 * 
 * PHP version 5
 *
 * @category   Packages
 *
 * @author     Dmitry Cherepovsky <cherep@jviba.com>
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       http://jviba.com/display/PhpDoc
 */

/**
 * TranslationBehavior provides an owner model with additional relations
 * and methods, which can be used to find a specific translation or to check,
 * if an object has any translations at all.
 * 
 * This behavior provides following relations to its owner model (when it's enabled):
 * <ul>
 *  <li>infos</li>
 *  <li>info - this relation needs a parameter with the key ':langId' set when it's used.</li>
 * </ul>
 * It also provides following properties to its owner model:
 * <ul>
 *  <li>isTranslated</li>
 * </ul>
 * 
 * PHP version 5
 *
 * @category   Packages
 *
 * @author     Dmitry Cherepovsky <cherep@jviba.com>
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       http://jviba.com/display/PhpDoc
 */
class TranslationBehavior extends CActiveRecordBehavior
{
    const EXCEPTION_EMPTY_MODEL_CLASS_NAME = '{class}: Model class name must be given.';
    const EXCEPTION_WRONG_MODEL_CLASS_GIVEN = '{class}: Wrong model class given.';
    const EXCEPTION_WRONG_CONFIG_GIVEN = '{method}: Wrong config given.';

    const DEFAULT_LANGUAGE_KEY = 'lang_id';
    const DEFAULT_FOREIGN_KEY = 'revision_id';

    /**
     * Name of an attribute of an owner model which contains language ID.
     *
     * @var string
     */
    public $languageKey = self::DEFAULT_LANGUAGE_KEY;

    /**
     * Name of an attribute of an owner model which is foreign key to behavior's owner table.
     *
     * @var string
     */
    public $foreignKey = self::DEFAULT_FOREIGN_KEY;

    /**
     * Name of a model class which implements multi-language 
     * information storage for an owner model.
     *
     * @var string
     */
    public $modelClassName;

    /**
     * Adds the relations specific for this behavior.
     */
    private function _addInfoRelations()
    {
        $metaData = $this->getOwner()->getMetaData();
        $metaData->addRelation(
            'infos',
            array(CActiveRecord::HAS_MANY, $this->modelClassName, $this->foreignKey)
        );
        $metaData->addRelation(
            'info',
            array(CActiveRecord::HAS_ONE, $this->modelClassName, $this->foreignKey)
        );
    }

    /**
     * Removes the relations specific for this behavior.
     */
    private function _removeInfoRelations()
    {
        $metaData = $this->getOwner()->getMetaData();
        $metaData->removeRelation('infos');
        $metaData->removeRelation('info');
    }

    /**
     * Attaches the behavior object to a model.
     *  
     * @param CActiveRecord $owner Owner instance
     * 
     * @throws CException If modelClassName is not set before attachment.
     */
    public function attach($owner)
    {
        if (empty($this->modelClassName)) {
            throw new CException(
                Yii::t('app', self::EXCEPTION_EMPTY_MODEL_CLASS_NAME, array('{class}' => __CLASS__))
            );
        }
        parent::attach($owner);
        $this->_addInfoRelations();
    }

    /**
     * Detaches the behavior object from the component.
     * 
     * @param CActiveRecord $owner Owner instance
     */
    public function detach($owner)
    {
        $this->_removeInfoRelations();
        parent::detach($owner);
    }

    /**
     * Enables or disables the behavior.
     * 
     * @param bool $value whether this behavior is enabled
     */
    public function setEnabled($value)
    {
        parent::setEnabled($value);
        if ($value) {
            if ($this->getOwner()) {
                $this->_addInfoRelations();
            }
        } else {
            if ($this->getOwner()) {
                $this->_removeInfoRelations();
            }
        }
    }

    /**
     * Checks if given objects have any translations in given language or any
     * translations at all. Takes an array of maps. Each map contains revision-object attributes.
     * 
     * Configuration array takes following options:
     *   - modelClassName
     *   - foreignKey  [optional] Default is 'revision_id'
     *   - languageKey [optional] Default is 'lang_id'
     *   - connection  [optional] Connection to a database
     * modelClassName, foreignKey, languageKey correspond to the options of this behavior.
     * 
     * @param array[]  &$objects An array of data. If an object has no ID, its
     *                           results won't be present in the returned array.
     * @param int|null $lang     Checks for translations in selected language.
     *                           Null means any translations omitted.
     * @param array    $config   Method configuration.
     * 
     * @return array|bool An array of the following format:
     *                    array(<object_id> => <boolean_check_result>, ...).
     * 
     * @static
     *
     * @throws CException When wrong properties given to the behavior
     */
    public static function isCollectionTranslated(&$objects, $lang, $config)
    {
        if (!isset($config['modelClassName'])) {
            throw new CException(
                Yii::t('app', self::EXCEPTION_WRONG_CONFIG_GIVEN, array('{method}' => __METHOD__))
            );
        }
        if (!class_exists($config['modelClassName'])) {
            throw new CException(
                Yii::t('app', self::EXCEPTION_WRONG_MODEL_CLASS_GIVEN, array('{class}' => __CLASS__))
            );
        }

        $foreignKey = isset($config['foreignKey'])
                    ? $config['foreignKey']
                    : self::DEFAULT_FOREIGN_KEY;
        $languageKey = isset($config['languageKey'])
                     ? $config['languageKey']
                     : self::DEFAULT_LANGUAGE_KEY;
        $modelClassName = $config['modelClassName'];
        $tableName = CActiveRecord::model($modelClassName)->tableName();

        $criteria = new CDbCriteria();
        $criteria->select = 't.'.$foreignKey;
        if ($lang) {
            $criteria->compare($languageKey, $lang);
        } else {
            $criteria->group = 't.'.$foreignKey;
        }
        $criteria->addInCondition($foreignKey, CHtml::listData($objects, 'id', 'id'));
        $connection = isset($config['connection']) ? $config['connection'] : Yii::app()->getDb();
        $command = $connection->getCommandBuilder()->createFindCommand($tableName, $criteria);
        $data = CHtml::listData($command->queryAll(), $foreignKey, $foreignKey);

        $result = array();
        foreach ($objects as $object) {
            $result[$object['id']] = in_array($object['id'], $data);
        }

        return $result;
    }

    /**
     * Getter for "isTranslated" property. 
     * 
     * Returns TRUE if the owner object has translations in the given language
     * 
     * @param int $lang Language ID. Null means any translations omitted
     * 
     * @return bool
     *
     * @throws CException When wrong properties given to the behavior
     */
    public function getIsTranslated($lang = null)
    {
        if (!class_exists($this->modelClassName)) {
            throw new CException(
                Yii::t('app', self::EXCEPTION_WRONG_MODEL_CLASS_GIVEN, array('{class}' => __CLASS__))
            );
        }
        $criteria = new CDbCriteria();
        $criteria->compare($this->foreignKey, $this->getOwner()->id);
        if ($lang) {
            $criteria->compare($this->languageKey, $lang);
        }

        return CActiveRecord::model($this->modelClassName)->exists($criteria);
    }

    /**
     * Gets an information record which is translated to the given language.
     * 
     * @param int|null $lang [optional] language ID. Null means any translations omitted.
     * 
     * @return CActiveRecord|null An information record or NULL, if couldn't find
     *                            any through the 'info' relation.
     */
    public function getTranslatedInfo($lang = null)
    {
        if ($lang) {
            $owner = $this->getOwner();
            if ($owner->hasRelated('info')) {
                if (!$info = $owner->getRelated('info')) {
                    return;
                } elseif ($info->{$this->languageKey} == $lang) {
                    return $info;
                }
            }

            return $owner->info(array(
                'condition' => $this->languageKey.'=:langId',
                'params' => array(':langId' => $lang),
            ));
        } else {
            return $this->getOwner()->info;
        }
    }

    /**
     * Deletes all info relations before deleting.
     *
     * @param CEvent $event event parameter
     */
    public function beforeDelete($event)
    {
        foreach ($this->getOwner()->infos as $info) {
            $info->delete();
        }
    }
}
