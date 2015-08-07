<?php
/**
 * FilterModel class
 *
 * PHP version 5
 *
 * @category Packages
 * @package  Ext.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/packages/php/docs
 */
/**
 * FilterModel is the base filter model class used for filtering data by html form
 * 
 * @category Packages
 * @package  Ext.model
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     https://jviba.com/packages/php/docs
 */
class FilterModel extends CFormModel implements IFilterModel
{
    const SCENARIO_FILTERING = 'filtering';
    
    /**
     * Constructor.
     * 
     * @param string $scenario name of the scenario that this model is used in.
     * 
     * @return void
     */
    public function __construct($scenario = self::SCENARIO_FILTERING)
    {
        parent::__construct($scenario);
    }
    
    /**
     * Displays errors whether its present
     * 
     * @return void
     * @see CModel::afterValidate()
     */
    protected function afterValidate()
    {
        if ($this->hasErrors()) {
            $errors = $this->getErrors();
            $this->displayErrors($errors);
        }
        parent::afterValidate();
    }
    
    /**
     * Applies filtering to existing criteria
     * 
     * @param CDbCriteria $criteria database criteria
     * 
     * @return void
     */
    public function apply($criteria)
    {
        if ($this->validate()) {
            $this->preFilter($criteria);
        }
    }
    
    /**
     * (non-PHPdoc)
     * 
     * @return void
     * @see IFilterModel::preFilter()
     */
    public function preFilter($criteria)
    {
        foreach ($this->getSafeAttributeNames() as $attribute) {
            $this->applyAttributeFilter($criteria, $attribute);
        }
    }
    
    /**
     * Applies filtering to existing criteria only for special filter's attribute
     * 
     * @param CDbCriteria $criteria  database criteria
     * @param string      $attribute attribute name
     * 
     * @return void
     */
    public function applyAttributeFilter($criteria, $attribute)
    {
    }
    
    /**
     * (non-PHPdoc)
     * 
     * @param array &$data pointer to data collection to filter
     * 
     * @return void
     * @see IFilterModel::postFilter()
     */
    public function postFilter(&$data)
    {
    }
    
    /**
     * Displays validation errors
     * 
     * @param array &$data errors map
     * 
     * @return void
     * @throws Exception
     */
    protected function displayErrors(&$data)
    {
        $errors = array();
        foreach (array_values($data) as $error) {
            $pieces = array_merge($errors, $error);
        }
        $params = array('{ERRORS}' => implode(', ', $errors));
        $message = 'Filter attributes validation errors: {ERRORS}.';
        $message = Yii::t('app', $message, $params);
        throw new ValidationException($message);
    }
}