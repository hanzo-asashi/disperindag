<?php
/**
 * CCachedFormDataBehavior class file.
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
/**
 * CCachedFormDataBehavior is the behavior class which
 * handles loading and caching form's internal data
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
class CCachedFormDataBehavior extends FormSubmitBehavior
{
    /**
     * Whether required to clear cache after success submitting
     * @var boolean
     */
    public $clearCachedAfterSubmit = true;
    
    /**
     * Data loaded via owner
     * @var mixed
     */
    private $_data;
    
    /**
     * Checks owner is cacheable entity
     * 
     * @param CModel $owner bahavior's owner
     * 
     * @return void
     * @see CBehavior::attach()
     * @throws Exception
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->_checkOwnerIsCachedEnity();
    }
    
    /**
     * Returns cached internal data loaded by form model
     * 
     * @param boolean $refresh whether required to reload data
     * 
     * @return mixed model's internal data
     */
    public function getInternalData($refresh = false)
    {
        $owner = $this->getOwner();
        if ($refresh) {
            $this->_data = null;
        }
        if (!isset($this->_data)) {
            $cache = Yii::app()->cache;
            $cacheId = $owner->getDataCacheId();
            if (($data = $cache->get($cacheId)) !== false) {
                $this->_data = $data;
            } else {
                $this->_data = $owner->loadData();
                $duration = $owner->getDataCacheDuration();
                $dependency = $owner->getDataCacheDependency();
                $cache->set($cacheId, $this->_data, $duration, $dependency);
            }
        }
        return $this->_data;
    }
    
    /**
     * Responds to {@link FormSubmitModel::onBeforeSubmit} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * You may set {@link CModelEvent::isValid} to be false to quit the submitting process.
     * 
     * @param CModelEvent $event event parameter
     * 
     * @return void
     */
    public function beforeSubmit($event)
    {
        parent::beforeSubmit($event);
        if ($this->getInternalData() === null) {
            $event->isValid = false;
        }
    }

    /**
     * Responds to {@link FormSubmitModel::onAfterSubmit} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * 
     * @param CModelEvent $event event parameter
     * 
     * @return void
     */
    public function afterSubmit($event)
    {
        if ($this->clearCachedAfterSubmit && $this->getOwner()->getIsDataChanged()) {
            $this->clearCachedData();
        }
    }
    
    /**
     * Clears cached data
     * 
     * @return void
     */
    public function clearCachedData()
    {
        $cacheId = $this->getOwner()->getDataCacheId();
        Yii::app()->cache->delete($cacheId);
        $this->_data = null;
    }
    
    /**
     * Checks whether owner is cached entity. If isn't true - throws exception
     * 
     * @return void
     * @throws Exception
     */
    private function _checkOwnerIsCachedEnity()
    {
        $owner = $this->getOwner();
        if (!($owner instanceof ICacheableEntity)) {
            $params = array('{CLASS}' => get_class($owner));
            $message = Yii::t('app', 'Owner class "{CLASS}" must be a cacheable entity. Please implements interface ICacheableEntity.', $params);
            throw new Exception($message);
        }
    }
}