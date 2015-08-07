<?php
/**
 * File for EventHandlingBehavior
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.handling
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 * @link       http://jviba.com/packages/php/docs
 */

/**
 * EventHandlingBehavior is a base class for
 * EventHandling's behavior.
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.handling
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 * @link       http://jviba.com/packages/php/docs
 */
class EventHandlingBehavior extends CBehavior
{
    /**
     * Behavior's special events
     * 
     * @return events configuration
     * @see CBehavior::events()
     */
    public function events()
    {
        return array(
            EventHandling::EVENT_BEFORE_HANDLE => 'beforeHandle',
            EventHandling::EVENT_ON_HANDLE => 'handle',
            EventHandling::EVENT_AFTER_HANDLE => 'afterHandle',
        );
    }
    
    /**
     * Responds to {@link EventHandling::onBeforeHandle} event.
     * 
     * @param HandlerEvent $event handling event
     * 
     * @return void
     */
    public function beforeHandle(HandlerEvent $event)
    {
    }
    
    /**
     * Responds to {@link EventHandling::onHandle} event.
     * 
     * @param HandlerEvent $event handling event
     * 
     * @return void
     */
    public function handle(HandlerEvent $event)
    {
    }
    
    /**
     * Responds to {@link EventHandling::onAfterHandle} event.
     * 
     * @param HandlerEvent $event handling event
     * 
     * @return void
     */
    public function afterHandle(HandlerEvent $event)
    {
    }
}