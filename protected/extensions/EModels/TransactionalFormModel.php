<?php
/**
 * TransactionalFormModel class
 *
 * PHP version 5
 *
 * @category Packages
 * @package  Ext.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/packages/php/docs
 * @abstract
 */
/**
 * TransactionalFormModel is the base model class used for processing form submit
 * with autostarting, submitting and rollbacking transaction
 * 
 * @category Packages
 * @package  Ext.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/packages/php/docs
 * @abstract
 */
abstract class TransactionalFormModel extends FormSubmitModel
{
    /**
     * Transactional model incapsulation level
     * @var integer
     */
    private static $_transactionalLevel = 0;
    
    /**
     * Database transaction
     * @var CDbTransaction
     */
    private $_transaction;
    
    /**
     * The default database connection for all transactional record classes.
     * @var CDbConnection
     * @see getDbConnection
     */
    public static $db;
    
    /**
     * Handles form presubmitting
     * 
     * @return bool whether form is presubmitted
     */
    protected function beforeSubmit()
    {
        $db = Yii::app()->db;
        if (!$this->_transaction = $db->getCurrentTransaction()) {
            $this->_transaction = $db->beginTransaction();
        } else {
            ++self::$_transactionalLevel;
        }
        return parent::beforeSubmit();
    }
    
    /**
     * Handles form postsubmitting
     * 
     * @return void
     */
    protected function afterSubmit()
    {
        parent::afterSubmit();
        $this->commitChanges();
    }
    
    /**
     * Calls whether submitting process failed
     * 
     * @param Exception $exception posible exception
     * 
     * @return void
     */
    protected function onSubmitFailure($exception = null)
    {
        $this->rollbackChanges();
        parent::onSubmitFailure($exception);
    }
    
    /**
     * Commits all changes to database
     * 
     * @return void
     */
    protected function commitChanges()
    {
        if ($this->_transaction->getActive()) {
            if (self::$_transactionalLevel > 0) {
                --self::$_transactionalLevel;
            } else {
                $this->_transaction->commit();
            }
        }
    }
    
    /**
     * Rollbacks changes applied with database
     * 
     * @return void
     */
    protected function rollbackChanges()
    {
        if ($this->_transaction->getActive()) {
            $this->_transaction->rollback();
        }
    }
    
    /**
     * Returns the database connection used by active record.
     * By default, the "db" application component is used as the database connection.
     * You may override this method if you want to use a different database connection.
     * 
     * @return CDbConnection the database connection used by active record.
     */
    public function getDbConnection()
    {
        if (self::$db !== null) {
            return self::$db;
        } else {
            self::$db = Yii::app()->getDb();
            if (self::$db instanceof CDbConnection) {
                return self::$db;
            } else {
                $message = Yii::t('packages', 'Transactional form model requires a "db" CDbConnection application component.');
                throw new CDbException($message);
            }
        }
    }
}