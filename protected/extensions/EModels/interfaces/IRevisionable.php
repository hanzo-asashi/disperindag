<?php
/**
 * IRevisionable interface
 * 
 * PHP version 5
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.interfaces
 * @author     Dmitry Cherepovsky <cherep@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       http://jviba.com/display/PhpDoc
 */

/**
 * IRevisionable is an interface which any ActiveRecord model must implement
 * if it supports revisions.
 * 
 * PHP version 5
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.interfaces
 * @author     Dmitry Cherepovsky <cherep@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       http://jviba.com/display/PhpDoc
 */
interface IRevisionable
{
    /**
     * This method is used to set {@link SandboxBehavior::$hasRevisionHistory} property.
     * 
     * @return boolean True if history revisions must be created. 
     */
    public function getHasRevisionHistory();
    
    /**
     * This method is used to set {@link SandboxBehavior::$revisionModel} property.
     * 
     * @return string Name of a class of a model which stores revision info.
     */
    public function getRevisionModel();
    
    /**
     * This method is used to set {@link SandboxBehavior::$revisionKey} property.
     * 
     * @return string Name of a relation with the revision model.
     */
    public function getRevisionKey();
    
    /**
     * This method is used to set {@link ArCounterBehavior::$counters} property.
     * 
     * @return string Name of the counter.
     */
    public function getRevisionCounterName();
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onBeforePublish()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onBeforePublish($event);
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterPublish()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterPublish($event);

    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onBeforeUnpublish()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onBeforeUnpublish($event);

    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterUnpublish()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterUnpublish($event);
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onBeforeRevert()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onBeforeRevert($event);
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterRevert()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterRevert($event);
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterCreateRevision()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterCreateRevision($event);
}