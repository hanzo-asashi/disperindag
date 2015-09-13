<?php

/**
 * File for EventHandling.
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * EventHandling is a model class for
 * handling abstract data processing streams.
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://jviba.com/packages/php/license GNU Public License
 *
 * @link       http://jviba.com/packages/php/docs
 */
class EventHandling extends CModel
{
    /**
     * Attributes names cache.
     *
     * @var array
     */
    private static $_names = array();

    /**
     * Event name which will be raised before handling process
     * should be started.
     *
     * @var string
     */
    const EVENT_BEFORE_HANDLE = 'onBeforeHandle';

    /**
     * Event name which will be raised when handling process
     * should be started.
     *
     * @var string
     */
    const EVENT_ON_HANDLE = 'onHandle';

    /**
     * Event name which will be raised after handling process
     * should be completed.
     *
     * @var string
     */
    const EVENT_AFTER_HANDLE = 'onAfterHandle';

    /**
     * The last handled event.
     *
     * @var HandlerEvent
     */
    private $_lastEvent;

    /**
     * Startup initialization.
     */
    public function init()
    {
        $this->attachBehaviors($this->behaviors());
    }

    /**
     * Starts handling event process, returns handling result.
     * 
     * @param HandlerEvent $event event which will be handler
     * 
     * @return mixed handling result, otherwise false
     */
    public function handle(HandlerEvent $event, $runValidation = true)
    {
        $event = $event === null ? $event = new HandlerEvent($this) : $event;
        $this->_lastEvent = $event;
        if (!$event->isValid || $event->handled) {
            return $event->result;
        }
        if (!$runValidation || $this->validate()) {
            try {
                if ($this->beforeHandle($event)) {
                    $this->onHandle($event);
                    if ($event->result !== false) {
                        $this->afterHandle($event);
                    }
                }

                return $event->result;
            } catch (Exception $e) {
                $this->onHandleError($e);
            }
        } else {
            $errors = $this->getErrors();
            $this->displayErrors($errors);
        }
    }

    /**
     * Displays validation errors.
     * 
     * @param array &$data errors map
     * 
     * @throws Exception
     */
    public function displayErrors(&$data)
    {
        $errors = array();
        foreach ($data as $name => $error) {
            $errors = array_merge($errors, $error);
        }
        $params = array('{ERRORS}' => implode($errors));
        $message = Yii::t('error', 'There are following validation errors: "{ERRORS}"', $params);
        throw new Exception($message);
    }

    /**
     * Calls whether handling process failed.
     * 
     * @param Exception $exception possible exception
     */
    protected function onHandleError($exception)
    {
        $message = $exception->getMessage();
        Yii::log($message, CLogger::LEVEL_ERROR);
        throw $exception;
    }

    /**
     * Calls before handling process should be started.
     * For breaking handling process, you must returns false.
     * 
     * @param HandlerEvent $event handling event
     * 
     * @return bool whether handling process should be started
     */
    protected function beforeHandle(HandlerEvent $event)
    {
        if ($this->hasEventHandler(self::EVENT_BEFORE_HANDLE)) {
            $this->onBeforeHandle($event);

            return $event->isValid;
        }

        return true;
    }

    /**
     * Raises before handling event.
     * 
     * @param HandlerEvent $event handling event
     */
    public function onBeforeHandle(HandlerEvent $event)
    {
        $this->raiseEvent(self::EVENT_BEFORE_HANDLE, $event);
    }

    /**
     * Raises handler event when process started.
     * 
     * @param HandlerEvent $event handling event
     */
    public function onHandle(HandlerEvent $event)
    {
        if ($this->hasEventHandler(self::EVENT_ON_HANDLE)) {
            $this->raiseEvent(self::EVENT_ON_HANDLE, $event);
        }
    }

    /**
     * Calls after handling process has been completed
     * with non-empty result.
     * 
     * @param HandlerEvent $event handling event
     */
    protected function afterHandle(HandlerEvent $event)
    {
        if ($this->hasEventHandler(self::EVENT_AFTER_HANDLE)) {
            $this->onAfterHandle($event);
        }
    }

    /**
     * Raises after handling event.
     * 
     * @param HandlerEvent $event handling event
     */
    public function onAfterHandle(HandlerEvent $event)
    {
        $this->raiseEvent(self::EVENT_AFTER_HANDLE, $event);
    }

    /**
     * Returns latest handled event.
     * 
     * @return HandlerEvent latest event
     */
    public function getLastEvent()
    {
        return $this->_lastEvent;
    }

    /**
     * Returns a property value, an event handler list or a behavior based on its name.
     * 
     * @param string $name the property name or event name
     * 
     * @return mixed the property value, event handlers attached to the event, or the named behavior
     *
     * @throws CException if the property or event is not defined
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (Exception $e) {
            if ($this->_lastEvent instanceof CEvent && $this->_lastEvent->canGetProperty($name)) {
                return $this->_lastEvent->$name;
            } else {
                throw $e;
            }
        }
    }

    /**
     * (non-PHPdoc).
     * 
     * @return array attributes names
     *
     * @see CModel::attributeNames()
     */
    public function attributeNames()
    {
        $className = get_class($this);
        if (!isset(self::$_names[$className])) {
            $class = new ReflectionClass(get_class($this));
            $names = array();
            foreach ($class->getProperties() as $property) {
                $name = $property->getName();
                if ($property->isPublic() && !$property->isStatic()) {
                    $names[] = $name;
                }
            }

            return self::$_names[$className] = $names;
        } else {
            return self::$_names[$className];
        }
    }
}
