<?php
/**
 * ActiveNotification class
 *
 * PHP version 5
 *
 * @category Packages
 * @package  Module.notifications.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/display/PhpDoc
 * @abstract
 */
/**
 * ActiveNotification is the base model class for all
 * notification models
 *
 * @property integer $id
 * @property integer $notification_id
 * 
 * @category Packages
 * @package  Module.notifications.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/display/PhpDoc
 * @abstract
 */
abstract class ActiveNotification extends CActiveRecord implements ISearchModel, IActiveNotification
{
    /**
     * Returns validation rules
     * 
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('notification_id', 'required'),
            array('notification_id', 'numerical', 'integerOnly'=>true),
            array('notification_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Returns model's relations
     * 
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'skeleton' => array(self::BELONGS_TO, 'Notification', 'notification_id'),
        );
    }
    
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * 
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     * @see ISearchModel::search();
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('notification_id', $this->notification_id);
        $config = array(
            'criteria' => $criteria,
        );
        return new CActiveDataProvider(get_class($this), $config);
    }
    
    /**
     * Sets notification data pointer
     * 
     * @param mixed &$notification notification data pointer
     * 
     * @return void
     */
    public function setNotificationData(&$notification)
    {
    }
    
    /**
     * Returns notification skeleton record
     * 
     * @return Notification notification skeleton
     */
    public function getSkeleton()
    {
        return $this->getRelated('skeleton');
    }
    
    /**
     * Changes notification skeleton record
     * 
     * @param Notification $skeleton notification skeleton
     * 
     * @return void
     */
    public function setSkeleton($skeleton)
    {
        $this->skeleton = $skeleton;
        $this->notification_id = $skeleton->id;
    }
}