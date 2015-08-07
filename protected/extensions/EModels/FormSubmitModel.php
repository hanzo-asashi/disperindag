<?php
/**
 * FormSubmitModel class
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
 * FormSubmitModel is the base model class used for processing form submit
 * 
 * @category Packages
 * @package  Ext.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/packages/php/docs
 * @abstract
 */
abstract class FormSubmitModel extends CFormModel
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    /**
     * Timestamp when form was rendered
     * @var integer
     */
    public $renderTime;
    
    /**
     * Whether model changes is submitted
     * @var bool
     */
    private $_isSubmitted = false;
    
    /**
     * Wheher need to throw an exception on validation error
     * @var boolean
     */
    private $_exceptOnValidationError = false;
    
    /**
     * (non-PHPdoc)
     * @see CFormModel::init()
     */
    public function init()
    {
        $this->renderTime = time();
        parent::init();
    }
    
    /**
     * (non-PHPdoc)
     * @see CModel::rules()
     */
    public function rules()
    {
        return array(
            array('renderTime', 'numerical', 'integerOnly' => true),
        );
    }
    
    /**
     * Changes model submitted state
     * 
     * @param bool $value model submitted state
     * 
     * @return void
     */
    public function setIsSubmitted($value)
    {
        $this->_isSubmitted = $value;
    }
    
    /**
     * Returns whether model is submitted
     * 
     * @return bool whether model is submitted
     */
    public function getIsSubmitted()
    {
        return $this->_isSubmitted;
    }
    
    /**
     * Sets whether need except on validation error
     * 
     * @param boolean $value whether need except
     * 
     * @return void
     */
    public function setExceptOnValidationError($value)
    {
        $this->_exceptOnValidationError = $value;
    }
    
    /**
     * Returns whether need except on validation error
     * 
     * @return boolean whether need except
     */
    public function getExceptOnValidationError()
    {
        return $this->_exceptOnValidationError;
    }
    
    /**
     * Initializes model attribute values
     * Override this method whether model required to get initial attributes
     * before working with it.
     * 
     * @return void
     */
    public function initAttributes()
    {
    }
    
    /**
     * Returns filled attributes data
     * 
     * @return array filled attibutes
     */
    public function getFilledAttributes()
    {
        $names = $this->getSafeAttributeNames();
        $attributes = array();
        foreach ($names as $name) {
            if (isset($this->$name)) {
                $attributes[$name] = $this->$name;
            }
        }
        return $attributes;
    }
    
    /**
     * Handles applying changes with database
     * 
     * @return bool whether changes applied successfully
     */
    protected abstract function onSubmit();
    
    /**
     * Handles form submit proccess
     * 
     * @param bool $runValidation whether validation required
     * 
     * @return bool whether form successfully submitted
     */
    public function submit($runValidation = true)
    {
        if (!$runValidation || $this->validate()) {
            if ($this->beforeSubmit()) {
                try {
                    if ($this->onSubmit()) {
                        $this->afterSubmit();
                        return true;
                    }
                    $this->onSubmitFailure();
                } catch (Exception $e) {
                    $this->onSubmitFailure($e);
                    throw $e;
                }
            }
        } else if ($this->getExceptOnValidationError()) {
            $errors = $this->getErrors();
            $this->displayErrors($errors);
        }
        return false;
    }
    
    /**
     * Handles form presubmitting
     * 
     * @return bool whether form is presubmitted
     */
    protected function beforeSubmit()
    {
        $this->setIsSubmitted(false);
        if ($this->hasEventHandler('onBeforeSubmit')) {
            $event = new CModelEvent($this);
            $this->onBeforeSubmit($event);
            return $event->isValid;
        }
        return true;
    }
    
    /**
     * Displays validation errors
     * 
     * @param array &$data errors map
     * 
     * @return void
     * @throws Exception
     */
    public function displayErrors(&$data)
    {
        $errors = array();
        foreach ($data as $name => $error) {
            $errors = array_merge($errors, $error);
        }
        $params = array('{ERRORS}' => implode(', ', $errors));
        $message = Yii::t('assert', 'There are following model validation errors: "{ERRORS}"', $params);
        throw new ValidationException($message);
    }
    
    /**
     * Handles form postsubmitting
     * 
     * @return void
     */
    protected function afterSubmit()
    {
        $this->setIsSubmitted(true);
        if ($this->hasEventHandler('onAfterSubmit')) {
            $this->onAfterSubmit(new CModelEvent($this));
        }
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
        if ($exception !== null) {
            $message = $exception->getMessage();
            Yii::log($message, 'error');
            $this->addError('exception', $message);
        }
    }
    
    /**
     * This method is invoked before the model submit process is completed successfully
     * 
     * @param CEvent $event event object
     * 
     * @return void
     */
    public function onBeforeSubmit($event)
    {
        $this->raiseEvent('onBeforeSubmit', $event);
    }
    
    /**
     * This method is invoked after the model submit process is completed successfully
     * 
     * @param CEvent $event event object
     * 
     * @return void
     */
    public function onAfterSubmit($event)
    {
        $this->raiseEvent('onAfterSubmit', $event);
    }
}