<?php

/**
 * DAOModel class.
 *
 * PHP version 5
 *
 * @category Packages
 *
 * @author   Pohil Alexandr <pohil@5-soft.com>
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link     https://jviba.com/packages/php/docs
 */
/**
 * DAOModel is the base model class used for processing form submit
 * with autostarting, submitting and rollbacking transaction.
 * 
 * @category Packages
 *
 * @author   Pohil Alexandr <pohil@5-soft.com>
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link     https://jviba.com/packages/php/docs
 */
class DAOModel extends TransactionalFormModel
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_LOAD = 'load';

    /**
     * Access object.
     *
     * @var mixed
     */
    private $_object;

    /**
     * Contruct of this class.
     * 
     * @param string $scenario object access scenario
     * @param mixed  $object   modifying object instance or id
     */
    public function __construct($scenario = self::SCENARIO_CREATE, $object = null)
    {
        if (isset($object)) {
            $this->setObject($object);
        }
        parent::__construct($scenario);
    }

    /**
     * Sets access object's.
     * 
     * @param mixed $value access object
     */
    public function setObject($value)
    {
        $this->_object = $value;
    }

    /**
     * Return object instance or id.
     * 
     * @return mixed object instance or id
     */
    public function getObject()
    {
        return $this->_object;
    }

    /**
     * Submits changes into database.
     *  
     * @return bool whether changes applied successfully
     *
     * @see FormSubmitModel::onSubmit()
     */
    protected function onSubmit()
    {
        $method = 'on'.$this->getScenario();

        return $this->$method();
    }
}
