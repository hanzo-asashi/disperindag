<?php

/**
 * This is the model class for table "{{kategori}}".
 *
 * The followings are the available columns in table '{{kategori}}':
 * @property string $kategori_id
 * @property string $kategori
 *
 * The followings are the available model relations:
 * @property Berita[] $beritas
 * @property Post[] $posts
 */
class Kategori extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{kategori}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kategori', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('kategori_id, kategori', 'safe', 'on'=>'search'),
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
			'beritas' => array(self::HAS_MANY, 'Berita', 'kategori_id'),
			'posts' => array(self::HAS_MANY, 'Post', 'post_kategori'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'kategori_id' => 'Kategori',
			'kategori' => 'Kategori',
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

		$criteria->compare('kategori_id',$this->kategori_id,true);
		$criteria->compare('kategori',$this->kategori,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Kategori the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
