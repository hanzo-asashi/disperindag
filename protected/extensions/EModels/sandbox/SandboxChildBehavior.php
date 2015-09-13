<?php

/**
 * File for SandboxChildBehavior.
 *
 * @category   Packages
 *
 * @author     Eugeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * SandboxChildBehavior is the behavior class created to handle events raised
 * by SandboxBehavior.
 *
 * @category   Packages
 *
 * @author     Eugeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       http://jviba.com/packages/php/docs
 */
class SandboxChildBehavior extends CActiveRecordBehavior
{
    /**
     * Declares events and the corresponding event handler methods.
     * 
     * @return array handled events
     *
     * @see CBehavior::events
     */
    public function events()
    {
        return CMap::mergeArray(
            parent::events(),
            array(
                'onBeforePublish' => 'beforePublish',
                'onAfterPublish' => 'afterPublish',
                'onBeforeUnpublish' => 'beforeUnpublish',
                'onAfterUnpublish' => 'afterUnpublish',
                'onBeforeRevert' => 'beforeRevert',
                'onAfterRevert' => 'afterRevert',
                'onAfterCreateRevision' => 'afterCreateRevision',
            )
        );
    }

    /**
     * Responds to {@link SandboxBehavior::onBeforePublish} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * You may set {@link CModelEvent::isValid} to be false to quit the validation process.
     * You may set $event->params['newAttributes'] to array, containing attributes
     * which will be set to the newly published revision.
     * 
     * @param CEvent $event event parameter
     */
    public function beforePublish($event)
    {
    }

    /**
     * Responds to {@link SandboxBehavior::onAfterPublish} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * 
     * @param CEvent $event event parameter
     */
    public function afterPublish($event)
    {
    }

    /**
     * Responds to {@link SandboxBehavior::onBeforeUnpublish} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * You may set {@link CModelEvent::isValid} to be false to quit the validation process.
     * You may set $event->params['newAttributes'] to array, containing attributes
     * which will be set to the newly published revision.
     * 
     * @param CEvent $event event parameter
     */
    public function beforeUnpublish($event)
    {
    }

    /**
     * Responds to {@link SandboxBehavior::onAfterUnpublish} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * 
     * @param CEvent $event event parameter
     */
    public function afterUnpublish($event)
    {
    }

    /**
     * Responds to {@link SandboxBehavior::onBeforeRevert} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * You may set {@link CModelEvent::isValid} to be false to quit the validation process.
     * The event['revision'] parameter should be passed.
     * 
     * @param CEvent $event event parameter
     */
    public function beforeRevert($event)
    {
    }

    /**
     * Responds to {@link SandboxBehavior::onAfterRevert} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * The event['revision'] parameter should be passed.
     * 
     * @param CEvent $event event parameter
     */
    public function afterRevert($event)
    {
    }

    /**
     * Responds to {@link SandboxBehavior::onAfterCreateRevision} event.
     * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
     * The event['revision'] parameter should be passed.
     * 
     * @param CEvent $event event parameter
     */
    public function afterCreateRevision($event)
    {
    }
}
