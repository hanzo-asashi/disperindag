<?php
/**
 * RevisionableActiveRecord class
 * 
 * PHP version 5
 *
 * @category Packages
 * @package  Ext.model
 * @author   Dmitry Cherepovsky <cherep@jviba.com>
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     http://jviba.com/packages/php/docs
 */
/**
 * RevisionableActiveRecord is a base abstract class for any activerecord
 * model which has revisionable features.
 * 
 * PHP version 5
 *
 * @category Packages
 * @package  Ext.model
 * @author   Dmitry Cherepovsky <cherep@jviba.com>
 * @author   Evgeniy Marilev <marilev@jviba.com>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     http://jviba.com/packages/php/docs
 */
abstract class RevisionableActiveRecord extends CActiveRecord implements IRevisionable
{
    /**
     * Returns behaviors of this model
     * 
     * @return array Behaviors
     */
    public function behaviors()
    {
        return array(
            'ArCounterBehavior' => array(
                'class' => 'ArCounterBehavior',
                'counters' => array($this->getRevisionCounterName())
            ),
            'SandboxBehavior' => array(
                'class' => 'SandboxBehavior',
                'hasRevisionHistory' => $this->getHasRevisionHistory(),
                'revisionModel' => $this->getRevisionModel(),
                'revisionKey' => $this->getRevisionKey()
            )
        );
    }
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onBeforePublish()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onBeforePublish($event)
    {
        $this->raiseEvent('onBeforePublish', $event);
    }
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterPublish()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterPublish($event)
    {
        $this->raiseEvent('onAfterPublish', $event);
    }
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onBeforeRevert()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onBeforeRevert($event)
    {
        $this->raiseEvent('onBeforeRevert', $event);
    }
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterRevert()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterRevert($event)
    {
        $this->raiseEvent('onAfterRevert', $event);
    }
    
    /**
     * Yii event scanner fails to recognize this {@link SandboxBehavior::onAfterCreateRevision()} 
     * raised on an owner of a SanboxBehavior without such stub.
     * 
     * @param CEvent $event handled event
     * 
     * @return void
     */
    public function onAfterCreateRevision($event)
    {
        $this->raiseEvent('onAfterCreateRevision', $event);
    }
}