<?php
/**
 * NotificationsModule class
 *
 * PHP version 5
 *
 * @category Packages
 * @package  Module.notifications
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/display/PhpDoc
 */
/**
 * NotificationsModule is the module class for
 * handling system notifications
 * 
 * @category Packages
 * @package  Module.notifications
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/display/PhpDoc
 */
class NotificationsModule extends CWebModule
{
    /**
     * Relative path to database migrations
     * @var string
     */
    public $migrationsPath = '/data';
    
    /**
     * Module initialization
     * 
     * @return void
     * @see CModule::init()
     */
    public function init()
    {
        parent::init();
        Yii::import('notifications.components.*');
        Yii::import('notifications.models.*');
        Yii::import('notifications.interfaces.*');
        if (!$this->hasComponent('manager')) {
            $this->setComponents(array(
                'manager' => array(
                    'class' => 'NotificationManager',
                    'config' => array(
                    ),
                ),
            ));
        }
    }
}