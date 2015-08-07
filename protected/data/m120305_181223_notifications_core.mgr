<?php
/**
 * m120305_181223_notifications_core class
 *
 * PHP version 5
 *
 * @category   Packages
 * @package    Module.notifications
 * @subpackage Module.notifications.data
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
/**
 * m120305_181223_notifications_core is the database migration class
 * which applies changes in database which is necessary for
 * basic notifications functionality
 *
 * PHP version 5
 *
 * @category   Packages
 * @package    Module.notifications
 * @subpackage Module.notifications.data
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
class m120305_181223_notifications_core extends CDbMigration
{
    /**
     * Process database upgrade under transaction
     * 
     * @return bool whether upgrade compelted successfully
     */
    public function safeUp()
    {
        $columns = array(
            'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
            'type' => 'INT(11) NOT NULL',
            'create_time' => 'TIMESTAMP NOT NULL',
            'PRIMARY KEY (`id`)',
            'KEY `fk_notification_type_idx` (`type`)',
            'KEY `fk_notification_create_time_idx` (`create_time`)',
        );
        $options = "ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci";
        $this->createTable('notification', $columns, $options);
    }
    
    /**
     * Process database downgrade under transaction
     * 
     * @return bool whether downgrade compelted successfully
     */
    public function safeDown()
    {
        $this->dropTable('notification');
    }
}