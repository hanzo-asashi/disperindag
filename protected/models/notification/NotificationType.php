<?php
/**
 * NotificationType class
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
 * NotificationType is the model class for table "notification_type".
 * The followings are the available columns in table 'notification_type':
 *
 * @property integer $id
 * @property string  $name
 * 
 * @category   Packages
 * @package    Module.notifications
 * @subpackage Module.notifications.model
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
class NotificationType extends CActiveRecord
{
    const TYPE_STATIC = 1;
}