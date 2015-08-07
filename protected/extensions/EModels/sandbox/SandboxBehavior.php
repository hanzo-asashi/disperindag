<?php
/**
 * File for SandboxBehavior
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Boris Serebrov <seb@algo-rithm.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * SandboxBehavior is the AR behavior class.
 * Sandbox main model behavior.
 *
 * Should be attached to the revision model:
 *    public function behaviors()
 *    {
 *         return array(
 *             'SandboxBehavior' => array(
 *                 'class' => 'application.models.sandbox.SandboxBehavior',
 *                 'hasRevisionHistory' => true,
 *                 'revisionModel' => 'PublisherProfileRevision',
 *             ),
 *         );
 *     }
 * (@link revisionModel} parameter is required - the name of the revision model class
 * {@link hasRevisionHistory} parameter allows to enable (true) or disable (false) intermediate revisions history
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.behavior
 * @author     Boris Serebrov <seb@algo-rithm.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       http://jviba.com/packages/php/docs
 */
class SandboxBehavior extends CActiveRecordBehavior
{
    const SANDBOX_REVISION = 0;
    const HISTORY_REVISION = 1;
    const PUBLISHED_REVISION = 2;
    
    const PUBLISH_ERROR = 0;
    const PUBLISH_SUCCESS = 1;
    const PUBLISH_NO_DATA_CHANGED = 2;

    /**
     *
     * Whether to support revisions history
     * false - only sandbox & published revisions
     * true - sandbox, published and history revisions
     * @var boolean
     */
    public $hasRevisionHistory = false;

    /**
     * Revision model name
     * @var CActiveRecord
     */
    public $revisionModel;

    /**
     * Relation revision model foreign key
     * @var string
     */
    public $revisionKey;

    /**
     * Field name where revision type is stored
     * @var string
     */
    public $revisionTypeField = 'revision_type';
    
    /**
     * Whether require create published revision when sandbox data not changed
     * @var bool
     */
    public $publishIfNoDataChanged = false;
    
    /**
     * Attach required relations and child behaviors
     * 
     * @return void
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $metaData = $owner->getMetaData();
        $metaData->addRelation('sandbox', array(
            CActiveRecord::HAS_ONE, $this->revisionModel, $this->revisionKey,
                'condition'=>'sandbox.' . $this->revisionTypeField . ' = ' . self::SANDBOX_REVISION
        ));
        $metaData->addRelation('published', array(
            CActiveRecord::HAS_ONE, $this->revisionModel, $this->revisionKey,
                'condition'=>'published.' . $this->revisionTypeField . ' = ' . self::PUBLISHED_REVISION
        ));
        $metaData->addRelation('revisions', array(
            CActiveRecord::HAS_MANY, $this->revisionModel, $this->revisionKey
        ));
        if ($this->hasRevisionHistory) {
            $metaData->addRelation('history_revisions', array(
                CActiveRecord::HAS_MANY, $this->revisionModel, $this->revisionKey,
                    'condition'=>'history_revisions.' . $this->revisionTypeField . ' = ' . self::HISTORY_REVISION
            ));
        }
    }

    /**
     * Find and return history revisions and published revision
     * @return array revision objects
     */
    /*public function findHistory()
    {
        $revision = $this->revisionModel();

        $criteria = new CDbCriteria;
        $criteria->addCondition($revision->{$this->revisionKey} . '=' . $this->owner->id);
        $criteria->addInCondition($revision->revisionTypeField,
            array(SandboxBehavior::HISTORY_REVISION,SandboxBehavior::PUBLISHED_REVISION)
        );

        return $revision->findAll($criteria);
    }*/

    /**
     * Try to publish sandbox revision to published revision and return publishing result
     * @return int publishing operation result 
     */
    public function publish()
    {
        if (!$this->publishIfNoDataChanged) {
            $changed = $this->sandboxChanged();
            if (!$changed) {
                return self::PUBLISH_NO_DATA_CHANGED;
            }
        }
        if (($newAttributes = $this->beforePublish()) !== false) {
            $publishResult = $this->hasRevisionHistory 
                       ? $this->publishWithHistory($newAttributes)
                       : $this->publishSandbox($newAttributes);
            $this->afterPublish();
            return $publishResult;
        }
        return self::PUBLISH_ERROR;
    }

    /**
     * Unpublish article revision
     *
     * @return integer unpublish result
     */
    public function unpublish()
    {
        if ($this->beforeUnpublish()) {
            if ($published = $this->getOwner()->published) {
                $published->{$this->revisionTypeField} = self::HISTORY_REVISION;
                
                if (!$published->save(false)) {
                    throw new CException('Can update published revision. ' . CHtml::errorSummary($published));
                }
            }
            $this->afterUnpublish();
            return self::PUBLISH_SUCCESS;
        } else {
            return self::PUBLISH_ERROR;
        }
    }
    
    /**
     * Checks sandbox for any changes
     * @return bool whether sandbox was changed
     */
    public function sandboxChanged()
    {
        $ret = false;
        $published = $this->getOwner()->published;
        if ($published) {
            $sandbox = $this->getOwner()->sandbox;
            return !$sandbox->compareWith($published, array($this->revisionTypeField)); 
        } else {
            return true;
        }
        return $ret;
    } 

    /**
     * Cancel sandbox and replace it with published revision content
     */
    public function cancel()
    {
        return $this->revert($this->getOwner()->published);
    }

    /**
     * Revert sandbox to the specified revision
     * 
     * @param integer|CActiveRecord $revision revision ID or record
     * 
     * @return boolean whether operation success
     */
    public function revert($revision)
    {
        if (!$revision) {
            return false;
        }
        $revision = $revision instanceof CActiveRecord
                  ? $revision
                  : $this->findRevisionById($revision);
        
        if ($this->beforeRevert($revision)) {
            $this->copyRevision($revision, $this->getOwner()->sandbox, array($this->revisionTypeField));
            $this->afterRevert($revision);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Returns whether sandbox owner implements revisionable
     * interface.
     * 
     * @return boolean whether owner is revisionable
     */
    protected function getWhetherRevisionableOwner()
    {
        return $this->getOwner() instanceof IRevisionable;
    }

    /**
     * This event is raised before publishing sandbox.
     * 
     * @param CEvent the event parameter
     * 
     * @return void
     * @see beforePublish
     */
    public function onBeforePublish($event)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->getOwner()->onBeforePublish($event);
        }
    }

    /**
     * This event is raised after the sandbox is published.
     * 
     * @param CEvent the event parameter
     * 
     * @return void
     */
    public function onAfterPublish($event)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->getOwner()->onAfterPublish($event);
        }
    }

    /**
     * This event is raised before unpublishing revision.
     * 
     * @param CEvent the event parameter
     * 
     * @return void
     * @see beforeUnpublish
     */
    public function onBeforeUnpublish($event)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->getOwner()->onBeforeUnpublish($event);
        }
    }

    /**
     * This event is raised after the revision is unpublished.
     * 
     * @param CEvent the event parameter
     * 
     * @return void
     */
    public function onAfterUnpublish($event)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->getOwner()->onAfterUnpublish($event);
        }
    }
    
    /**
     * This event is raised before the sandbox is reverted.
     * 
     * @param CEvent the event parameter
     * 
     * @return void
     */
    public function onBeforeRevert($event)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->getOwner()->onBeforeRevert($event);
        }
    }

    /**
     * This event is raised after the sandbox is reverted.
     * 
     * @param CEvent the event parameter
     * 
     * @return void
     */
    public function onAfterRevert($event)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->getOwner()->onAfterRevert($event);
        }
    }

    /**
     * Find specified revision by revision ID
     * 
     * @param integer $revisionId revision id to find
     * 
     * @return revision object or null if revision not found
     */
    public function findRevisionById($revisionId)
    {
        $model = $this->revisionModel();
        return $model->findByPk($revisionId);
    }
    
    /**
     * Find specified revision by revision type
     * 
     * @param integer $revisionType revision type
     * 
     * @return revision object or null if revision not found
     */
    public function findRevisionByType($revisionType)
    {
        $model = $this->revisionModel();
        $attributes = array($this->revisionTypeField => $revisionType);
        return $model->findByAttributes($attributes);
    }
    
    /**
     * This method is invoked before publishing a sandbox.
     * The default implementation raises the {@link onBeforePublish} event.
     * You may override this method to do any work before publishing.
     * Make sure you call the parent implementation so that the event is raised properly.
     * 
     * @return array|boolean False if publishing should not be executed. Array of 
     * attributes to be set to revision before the publishing.
     */
    protected function beforePublish()
    {
        if ($this->getWhetherRevisionableOwner()) {
            $event = new CModelEvent($this);
            $this->onBeforePublish($event);
            $areNewAttributesValid = isset($event->params['newAttributes'])
                && is_array($event->params['newAttributes']);
            return $event->isValid && $areNewAttributesValid 
                ? $event->params['newAttributes']
                : false;
        } else {
            return true;
        }
    }

    /**
     * This method is invoked after publishing a sandbox successfully.
     * The default implementation raises the {@link onAfterPublish} event.
     * You may override this method to do postprocessing after publishing.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterPublish()
    {
        $this->getOwner()->getRelated('published', true);
        if ($this->getWhetherRevisionableOwner()) {
            $this->onAfterPublish(new CModelEvent($this));
        }
    }

    /**
     * This method is invoked before unpublishing a revision.
     * The default implementation raises the {@link onBeforeUnpublish} event.
     * You may override this method to do any work before publishing.
     * Make sure you call the parent implementation so that the event is raised properly.
     * 
     * @return boolean
     */
    protected function beforeUnpublish()
    {
        if ($this->getWhetherRevisionableOwner()) {
            $event = new CModelEvent($this);

            $this->onBeforeUnpublish($event);

            return $event->isValid;
        } else {
            return true;
        }
    }

    /**
     * This method is invoked after unpublishing a revision successfully.
     * The default implementation raises the {@link onAfterUnpublish} event.
     * You may override this method to do postprocessing after publishing.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterUnpublish()
    {
        if ($this->getWhetherRevisionableOwner()) {
            $this->onAfterUnpublish(new CModelEvent($this));
        }
    }
    
    /**
     * This method is invoked before sandbox reverted to the specified history revision.
     * The default implementation raises the {@link onBeforeRevert} event.
     * You may override this method to do preventing reverting.
     * Make sure you call the parent implementation so that the event is raised properly.
     * 
     * @param mixed $revision revision to revert
     * 
     * @return boolean whether reverting should be processed
     */
    protected function beforeRevert($revision)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $event = new CModelEvent($this, compact('revision'));
            $this->onBeforeRevert($event);
            return $event->isValid;
        } else {
            return true;
        }
    }

    /**
     * This method is invoked after sandbox changes are reverted.
     * The default implementation raises the {@link onAfterRevert} event.
     * You may override this method to do postprocessing after reverting.
     * Make sure you call the parent implementation so that the event is raised properly.
     * 
     * @param mixed $revision revision to revert
     * 
     * @return void
     */
    protected function afterRevert($revision)
    {
        $this->getOwner()->getRelated('sandbox', true);
        if ($this->getWhetherRevisionableOwner()) {
            $event = new CModelEvent($this, compact('revision'));
            $this->onAfterRevert($event);
        }
    }

    /**
     * Publish sandbox with history.
     * Old published revision becomes history, sandbox is copied to new published revision
     * 
     * @param array $newAttributes Attributes to be set to published revision.
     * 
     * @return integer publishing result
     */
    protected function publishWithHistory($newAttributes = array())
    {
        $owner = $this->getOwner();
        $newRevision = $this->createRevision(SandboxBehavior::PUBLISHED_REVISION);
        $oldPublished = $owner->published;
        //if (!$oldPublished) $oldPublished = $this->findPublished();

        if ($oldPublished) {
            $oldPublished->{$this->revisionTypeField} = SandboxBehavior::HISTORY_REVISION;
            if (!$oldPublished->save(false)) {
                throw new CException("Can not save history revision. " . CHtml::errorSummary($oldPublished));
            }
        }
        $ret = $this->copyRevision(
            $owner->sandbox, 
            $newRevision, 
            array($this->revisionTypeField),
            $newAttributes
        );
        $ret = $ret ? self::PUBLISH_SUCCESS : self::PUBLISH_ERROR;
        return $ret;
    }

    /**
     * Publish sandbox without history
     * Sandbox is copied to the published revision
     * 
     * @param array $newAttributes [optional] Attributes to be set to published revision.
     * 
     * @return integer Publishing result
     */
    protected function publishSandbox($newAttributes = array())
    {
        $owner = $this->getOwner();
        $published = $owner->published;
        if (!$published) {
            $published = $this->createRevision(SandboxBehavior::PUBLISHED_REVISION);
        }
        $ret = $this->copyRevision(
            $owner->sandbox, 
            $published, 
            array($this->revisionTypeField),
            $newAttributes
        );
        $ret = $ret ? self::PUBLISH_SUCCESS : self::PUBLISH_ERROR;
        return $ret;
    }

    /**
     * Copy one revision to another.
     * Revision class should have CopyBehavior
     * 
     * @param CActiveRecord $from revision to copy from
     * @param CActiveRecord $to revision to copy to
     * @param array         $skipAttributes Attributes not to copy
     * @param array         $newAttributes Attributes to be set to copied revision
     * 
     * @return 
     */
    public function copyRevision($from, $to, $skipAttributes = array(), $newAttributes = array())
    {
        if ($to->asa('CopyBehavior')) {
            return $to->copyFrom($from, $skipAttributes, $newAttributes);
        } else {
            $revClass = get_class($to);
            throw new CException("Revision class $revClass does not have CopyBehavior");
        }
    }

    /**
     * After construct event handler - creates sandbox revision
     * @param CEvent $event
     */
    public function afterConstruct($event)
    {
        $this->getOwner()->sandbox = $this->createRevision(SandboxBehavior::SANDBOX_REVISION);
    }

    /**
     * After save event handler
     * Saves sandbox revision
     * 
     * @return void
     */
    public function afterSave($event)
    {
        $owner = $this->getOwner();
        $sandbox = $owner->sandbox;
        if ($sandbox->getIsNewRecord()) {
            $sandbox->{$this->revisionKey} = $owner->id;
            if (!$sandbox->save(false)) {
                throw new CException("Can not save sandbox. " . CHtml::errorSummary($sandbox));
            }
        }
    }

    /**
     * Calls model() method from revision class
     * 
     * @return CActiveRecord revision model instance
     */
    public function revisionModel()
    {
        return call_user_func(array($this->revisionModel, 'model'));
    }

    /**
     * Create new revision
     * 
     * @param integer $revisionType new revision type
     * 
     * @return CActiveRecord new revision record
     */
    protected function createRevision($revisionType = null)
    {
        $revision = new $this->revisionModel;
        if (isset($revisionType)) {
            $revision->{$this->revisionKey} = $this->owner->id;
            $revision->{$this->revisionTypeField} = $revisionType;
        }
        $this->afterCreateRevision($revision);
        return $revision;
    }
    
    /**
     * This method is invoked after constructing new revision instance
     * 
     * @param CActiveRecord $revision revision record
     * 
     * @return void
     */
    protected function afterCreateRevision($revision)
    {
        if ($this->getWhetherRevisionableOwner()) {
            $event = new CEvent($this);
            $event->params['revision'] = $revision;
            $this->getOwner()->onAfterCreateRevision($event);
        }
    }

    /**
     * Returns if the current owner record is published.
     *
     * @return boolean whether the record is published
     */
    public function getIsPublished()
    {
        return $this->getOwner()->published !== null;
    }
}
