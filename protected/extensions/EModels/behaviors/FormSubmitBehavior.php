<?php
/**
 * FormSubmitBehavior class file.
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
/**
 * FormSubmitBehavior is the base class for behaviors that
 * can be attached to {@link FormSubmitModel}.
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
class FormSubmitBehavior extends CModelBehavior
{
    /**
     * Declares events and the corresponding event handler methods.
     * 
     * @return array events (array keys) and the corresponding event handler methods (array values).
     * @see CBehavior::events
     */
    public function events()
    {
        $base = parent::events();
        $additional = array(
            'onBeforeSubmit' => 'beforeSubmit',
            'onAfterSubmit' => 'afterSubmit',
        );
        return array_merge($base, $additional);
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
    }
}