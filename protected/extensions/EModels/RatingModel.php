<?php
/**
 * RatingModel class
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
 * RatingModel is the base model class for rating models
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
abstract class RatingModel extends CModel implements ICacheableEntity
{
    /**
     * Rating cache
     * @var mixed
     */
    private $_rating;
    
    /**
     * Calculates rating and returns it's value
     * 
     * @return mixed rating value
     */
    protected abstract function calc();
    
    /**
     * Loads internal entity's data
     * Do not call this method manually
     * 
     * @return mixed loaded data
     * @see ICacheableEntity::loadData()
     */
    public function loadData()
    {
        return $this->calc();
    }
    
    /**
     * Returns rating cache dependency
     * 
     * @return mixed cache dependency
     * @see ICacheableEntity::getDataCacheDependency()
     */
    public function getDataCacheDependency()
    {
        return null;
    }
    
    /**
     * Returns whether form data was changed
     * 
     * @return boolean whether data was changed
     * @see ICacheableEntity::getIsDataChanged()
     */
    public function getIsDataChanged()
    {
        $cacheId = $this->getDataCacheId();
        return Yii::app()->cache->get($cacheId) === false;
    }
    
    /**
     * Startup initialization
     * 
     * @return void
     */
    public function init()
    {
    }
    
    /**
     * Returns the list of attribute names of the model
     * 
     * @return array list of attribute names
     * @see CModel::attributeNames()
     */
    public function attributeNames()
    {
        return array();
    }
    
    /**
     * Returns cached rating value
     * 
     * @return mixed rating value
     */
    public function getCachedValue()
    {
        return $this->_rating;
    }
    
    /**
     * Directly clears cached rating value
     * 
     * @return boolean whether operation completed successfully
     */
    public function clearCache()
    {
        $cacheId = $this->getDataCacheId();
        Yii::app()->cache->delete($cacheId);
        return true;
    }
    
    /**
     * Returns rating value and caches it before returning
     * 
     * @param bool $refresh whether required to recalculate and recache rating
     * 
     * @return mixed rating value
     */
    public function getValue($refresh = false)
    {
        if ($refresh || !isset($this->_rating)) {
            if ($this->validate()) {
                if ($refresh ? $this->clearCache() : $this->beforeCalculate()) {
                    $this->_rating = $this->calc();
                    $this->afterCalculate();
                }
            } else {
                $errors = $this->getErrors();
                $this->displayErrors($errors);
            }
        }
        return $this->_rating;
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
    
    /**
     * Handles callback before calculating rating
     * 
     * @return bool whether rating should be recalculated
     */
    protected function beforeCalculate()
    {
        $cacheId = $this->getDataCacheId();
        $ret = ($this->_rating = Yii::app()->cache->get($cacheId)) === false;
        return $ret;
    }
    
    /**
     * Handles callback after calculating rating
     * 
     * @return void
     */
    protected function afterCalculate()
    {
        $cacheId = $this->getDataCacheId();
        $duration = $this->getDataCacheDuration();
        $dependency = $this->getDataCacheDependency();
        Yii::app()->cache->set($cacheId, $this->_rating, $duration, $dependency);
    }
}