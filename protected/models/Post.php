<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property string $post_id
 * @property string $isi_post
 * @property string $url_post
 * @property string $post_slug
 * @property string $post_title
 * @property string $post_kategori
 * @property string $post_date
 * @property string $post_update_date
 * @property integer $is_publish
 *
 * The followings are the available model relations:
 * @property StatusPublish $isPublish
 * @property Kategori $postKategori
 */
class Post extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_update_date', 'required'),
			array('is_publish', 'numerical', 'integerOnly'=>true),
			array('url_post', 'length', 'max'=>100),
			array('post_slug', 'length', 'max'=>50),
			array('post_title', 'length', 'max'=>150),
			array('post_kategori', 'length', 'max'=>11),
			array('isi_post, post_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('post_id, isi_post, url_post, post_slug, post_title, post_kategori, post_date, post_update_date, is_publish', 'safe', 'on'=>'search'),
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
			'isPublish' => array(self::BELONGS_TO, 'StatusPublish', 'is_publish'),
			'postKategori' => array(self::BELONGS_TO, 'Kategori', 'post_kategori'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'post_id' => 'Post',
			'isi_post' => 'Isi Post',
			'url_post' => 'Url Post',
			'post_slug' => 'Post Slug',
			'post_title' => 'Post Title',
			'post_kategori' => 'Post Kategori',
			'post_date' => 'Post Date',
			'post_update_date' => 'Post Update Date',
			'is_publish' => 'Is Publish',
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

		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('isi_post',$this->isi_post,true);
		$criteria->compare('url_post',$this->url_post,true);
		$criteria->compare('post_slug',$this->post_slug,true);
		$criteria->compare('post_title',$this->post_title,true);
		$criteria->compare('post_kategori',$this->post_kategori,true);
		$criteria->compare('post_date',$this->post_date,true);
		$criteria->compare('post_update_date',$this->post_update_date,true);
		$criteria->compare('is_publish',$this->is_publish);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
