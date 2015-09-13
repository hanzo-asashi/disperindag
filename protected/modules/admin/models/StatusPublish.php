<?php

/**
 * This is the model class for table "{{status_publish}}".
 *
 * The followings are the available columns in table '{{status_publish}}':
 * @property integer $status_publish_id
 * @property string $nama_status
 *
 * The followings are the available model relations:
 * @property Berita[] $beritas
 * @property Post[] $posts
 */
class StatusPublish extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{status_publish}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama_status', 'required'),
			array('nama_status', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('status_publish_id, nama_status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'beritas' => array(self::HAS_MANY, 'Berita', 'is_publish'),
			'posts' => array(self::HAS_MANY, 'Post', 'is_publish'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'status_publish_id' => 'Status Publish',
			'nama_status' => 'Nama Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('status_publish_id',$this->status_publish_id);
		$criteria->compare('nama_status',$this->nama_status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StatusPublish the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
