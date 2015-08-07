<?php
/**
 * Notification class
 *
 * PHP version 5
 *
 * @category   Packages
 * @package    Module.notifications
 * @subpackage Module.notifications.model
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
/**
 * Notification is the model class for table "notification".
 * The followings are the available columns in table 'notification':
 *
 * @property integer $id
 * @property integer $type
 * @property string  $create_time
 * 
 * @category   Packages
 * @package    Module.notifications
 * @subpackage Module.notifications.model
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
class Notification extends CActiveRecord
{
    /**
     * Notification's data
     * @var mixed
     */
    private $_data;
    
    /**
     * Returns the static model of the specified AR class.
     * 
     * @param string $className the name of the model class
     * 
     * @return Notification the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns associated table name
     * 
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'notification';
    }
    
    /**
     * Returns model's behaviors configuration
     * 
     * @return array model's behaviors
     * @see CModel::behaviors()
     */
    public function behaviors()
    {
        return array(
            'timestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }

    /**
     * Returns validation rules
     * 
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('type', 'required'),
            array('type', 'numerical', 'integerOnly' => true, 'min' => 1),
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
            'delivery' => array(self::HAS_ONE, 'Delivery', 'notification_id'),
        );
    }
    
    /**
     * Notification data
     * 
     * @param mixed $data notification data pointer
     * 
     * @return void
     */
    public function setData($data)
    {
        $this->_data = $data;
    }
    
    /**
     * Returns notification data pointer
     * 
     * @return mixed $data notification data pointer
     */
    public function getData()
    {
        return $this->_data;
    }
}