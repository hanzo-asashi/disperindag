<?php

/**
 * File for RevisionBehavior.
 *
 * @category   Packages
 *
 * @author     Boris Serebrov <seb@algo-rithm.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       http://jviba.com/packages/php/docs
 */
/**
 * RevisionBehavior is the AR behavior class.
 * Sandbox revision behavior.
 *
 * Should be attached to the revision model:
 *    public function behaviors() {
 *        return array(
 *            'RevisionBehavior' => array(
 *                'class' => 'application.models.sandbox.RevisionBehavior',
 *                'sandboxField' => 'publisher_profile_id',
 *            ),
 *        );
 *    }
 * sandboxField parameter is required - field name where main sandbox model id is stored
 *
 * @category   Packages
 *
 * @author     Boris Serebrov <seb@algo-rithm.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       http://jviba.com/packages/php/docs
 */
class RevisionBehavior extends CActiveRecordBehavior
{
    /**
     * sanbox revision.
     *
     * @var int
     */
    const SANDBOX = 0;

    /**
     * history revision.
     *
     * @var int
     */
    const HISTORY = 1;

    /**
     * published revision.
     *
     * @var int
     */
    const PUBLISHED = 2;

    /**
     * The name of main sandboc model class.
     *
     * @var string
     */
    public $sandboxModelClass;

    /**
     * Field name where main sandbox model id is stored.
     *
     * @var string
     */
    public $sandboxField;

    /**
     * Field name where revision type is stored.
     *
     * @var string
     */
    public $revisionTypeField = 'revision_type';

    /**
     * Field name where revision name is stored.
     *
     * @var string
     */
    public $revisionNameField = 'revision_name';

    /**
     * Field name where revision name is stored.
     *
     * @var string
     */
    public $revisionCreatorField = 'creator_id';

    /**
     * The name of the relation which will be added to the owner model.
     *
     * @var string
     */
    public $sandboxRelationName = 'sandbox';

    /**
     * Revision label template.
     *
     * @var string
     */
    public $revisionLabelTemplate = 'Revision {n}';

    /**
     * Get field name where main sandbox model id is stored.
     * 
     * @return string
     */
    public function getSandboxField()
    {
        return $this->sandboxField;
    }

    /**
     * Get field name where revision type is stored.
     * 
     * @return string
     */
    public function getRevisionTypeField()
    {
        return $this->revisionTypeField;
    }

    /**
     * Returns revision label.
     *
     * @param int $counter revision counter value
     *
     * @return string revision label
     */
    public function getRevisionLabel($counter)
    {
        $vars = array('{n}' => $counter);

        return strtr($this->revisionLabelTemplate, $vars);
    }

    /**
     * Get main sandbox model id.
     * 
     * @return int sandbox ID
     */
    public function getSandboxId()
    {
        return $this->owner->{$this->sandboxField};
    }

    /**
     * Get main sandbox model.
     */
    public function getSandbox()
    {
        return $this->owner->{$this->sandboxRelationName};
    }

    /**
     * Set main sandbox model id.
     * 
     * @param int $value sandbox ID
     */
    public function setSandboxId($value)
    {
        $this->owner->{$this->sandboxField} = $value;
    }

    /**
     * Get revision type.
     * 
     * @return int revision type
     */
    public function getRevisionType()
    {
        return $this->owner->{$this->revisionTypeField};
    }

    /**
     * Set revision type.
     * 
     * @param int $value revision type
     */
    public function setRevisionType($value)
    {
        $this->owner->{$this->revisionTypeField} = $value;
    }

    /**
     * Get revision name.
     * 
     * @return string revision name
     */
    public function getRevisionName()
    {
        return $this->owner->{$this->revisionNameField};
    }

    /**
     * Set revision name.
     * 
     * @param string $value revision name
     */
    public function setRevisionName($value)
    {
        $this->owner->{$this->revisionNameField} = $value;
    }

    /**
     * Get creator ID.
     * 
     * @return mixed revision creator, false if unknown
     */
    public function getCreatorId()
    {
        if ($this->revisionCreatorField) {
            $owner = $this->getOwner();
            $field = $this->revisionCreatorField;
            if (isset($owner->$field)) {
                return $owner->$field;
            }
        }

        return false;
    }

    /**
     * Set creator ID.
     * 
     * @param mixed $value creator ID
     */
    public function setCreatorId($value)
    {
        if ($this->revisionCreatorField) {
            $owner = $this->getOwner();
            $field = $this->revisionCreatorField;
            if (isset($owner->$field)) {
                $owner->$field = $value;
            }
        }
    }

    /**
     * Adds relation to the sandbox when attaching to the owner model.
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $owner->getMetaData()->addRelation($this->sandboxRelationName, array(
            CActiveRecord::BELONGS_TO, $this->sandboxModelClass, $this->getSandboxField(),
        ));
    }

    /**
     * Overrides parent implementation to set owner's creator and revision name.
     *
     * @see CActiveRecordBehavior::beforeSave()
     */
    public function beforeSave($event)
    {
        if ($this->owner->isNewRecord) {
            $app = Yii::app();
            if (($user = $app->getComponent('user')) && !$user->getIsGuest()) {
                $this->setCreatorId($user->getLogged()->id);
            }
            if ($this->getRevisionType() != SandboxBehavior::SANDBOX_REVISION) {
                $counter = $this->getSandbox()->getCounter('revision');
                $this->setRevisionName($this->getRevisionLabel($counter));
            }
        }

        return parent::beforeSave($event);
    }

    /**
     * Overrides parent implementation to increase owner's revision counter.
     *
     * @see CActiveRecordBehavior::afterSave()
     */
    public function afterSave($event)
    {
        if ($this->owner->isNewRecord) {
            if ($this->getRevisionType() != SandboxBehavior::SANDBOX_REVISION) {
                $this->getSandbox()->incCounter('revision');
            }
        }

        return parent::afterSave($event);
    }
}
