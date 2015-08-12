<?php

/**
 * File for LogBehavior.
 *
 * @category Packages
 *
 * @author   Roman Ischenko <ischenko@softlogicgroup.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link     https://jviba.com/display/PhpDoc/packages
 */

/**
 * LogBehavior is a behavior for performing log operations inside of component(s).
 *
 * @category Packages
 *
 * @author   Roman Ischenko <ischenko@softlogicgroup.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link     https://jviba.com/display/PhpDoc/packages
 *
 * @property callback onAfterLog
 */
class LogBehavior extends CBehavior
{
    /**
     * @var string specific category name
     */
    public $category = null;

    /**
     * Enables output log data into STDOUT whether console application.
     * 
     * @param CComponent $owner behavior owner
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if (Yii::app() instanceof CConsoleApplication) {
            $this->attachEventHandler('onAfterLog', array('LogBehavior', 'stdout'));
        }
    }

    /**
     * Write message with INFO level.
     *
     * @param string $message message
     */
    public function info($message)
    {
        $this->log($message, CLogger::LEVEL_INFO);
    }

    /**
     * Write message with ERROR level.
     *
     * @param string $message message
     */
    public function error($message)
    {
        $this->log($message, CLogger::LEVEL_ERROR);
    }

    /**
     * Write message with TRACE level.
     *
     * @param string $message message
     */
    public function trace($message)
    {
        $this->log($message, CLogger::LEVEL_TRACE);
    }

    /**
     * Write message with WARNING level.
     *
     * @param string $message message
     */
    public function warning($message)
    {
        $this->log($message, CLogger::LEVEL_WARNING);
    }

    /**
     * Raised right after message was logged.
     *
     * @param CEvent $event instance
     */
    public function onAfterLog($event)
    {
        $this->raiseEvent('onAfterLog', $event);
    }

    /**
     * Action performed after log was written.
     *
     * @param string $message  message
     * @param string $level    message priority
     * @param string $category message category
     */
    protected function afterLog($message, $level, $category)
    {
        if ($this->hasEventHandler('onAfterLog')) {
            $event = new CEvent($this, array(
                'level' => $level,
                'message' => $message,
                'category' => $category,
            ));
            $this->onAfterLog($event);
        }
    }

    /**
     * Put log message to logger.
     *
     * @param string $message log message
     * @param string $level   log level
     */
    public function log($message, $level)
    {
        $category = empty($this->category)
            ? $this->resolveCategory() : $this->category;

        if (is_object($message)) {
            if ($message instanceof Exception) {
                $message = sprintf(
                    "Exception '%s': [Message: '%s'; Code: %d; File: '%s'; Line: %d]",
                    get_class($message), $message->getMessage(), $message->getCode(), $message->getFile(), $message->getLine()
                );
            }
        }

        Yii::log($message, $level, $category);
        $this->afterLog($message, $level, $category);
    }

    /**
     * Outputs log event's data into STDOUT.
     * 
     * @param CEvent $event logger event
     * 
     * @static
     */
    public static function stdout(CEvent $event)
    {
        if (is_resource(STDOUT)) {
            echo $event->params['message']."\n";
        }
    }

    /**
     * Resolve dynamic category name.
     *
     * @return string category name
     */
    protected function resolveCategory()
    {
        $category = 'application';
        $owner = $this->getOwner();
        if ($owner instanceof CController) {
            $action = $owner->getAction();
            $category = "controller.{$owner->getUniqueId()}";
            $category = empty($action)
                ? $category : "{$category}.{$action->getId()}";
        } elseif ($owner instanceof CConsoleCommand) {
            $category = "command.{$owner->getName()}";
        } elseif ($owner instanceof CApplicationComponent) {
            $category = 'component.'.strtolower(get_class($owner));
        } elseif ($owner instanceof CModel) {
            $category = 'model.'.strtolower(get_class($owner));
        }

        return $category;
    }
}
