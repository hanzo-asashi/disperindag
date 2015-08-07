<?php
/**
 * File for HandlerEvent
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.handling
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 * @link       http://jviba.com/packages/php/docs
 */

/**
 * HandlerEvent is a event class for raising
 * events into EventHandling model's data processing circuit
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.handling
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 * @link       http://jviba.com/packages/php/docs
 */
class HandlerEvent extends CModelEvent
{
    /**
     * Handling result, otherwise false
     * @var mixed
     */
    public $result = false;
    
    /**
     * Constructor.
     * 
     * @param mixed $sender sender of the event
     * @param mixed $params additional parameters for the event
     * 
     * @return void
     */
    public function __construct($sender = null, $params = null)
    {
        parent::__construct($sender, $params);
        $this->init();
    }
    
    /**
     * Startup initialization
     * 
     * @return void
     */
    public function init()
    {
    }
}