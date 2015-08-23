<?php

/**
 * This is the model class for table "{{berita}}".
 *
 * The followings are the available columns in table '{{berita}}':
 * @property string $berita_id
 * @property string $isi_berita
 * @property string $url
 * @property string $judul
 * @property string $image_path
 * @property string $tgl_berita
 * @property string $tgl_update
 * @property string $tags_id
 * @property string $kategori_id
 * @property integer $is_publish
 *
 * The followings are the available model relations:
 * @property Kategori $kategori
 * @property StatusPublish $isPublish
 * @property Tags $tags
 */
class Berita extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{berita}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kategori_id', 'required'),
			array('is_publish', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>20),
			array('judul', 'length', 'max'=>50),
			array('image_path', 'length', 'max'=>255),
			array('tags_id, kategori_id', 'length', 'max'=>11),
			array('isi_berita, tgl_berita, tgl_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('berita_id, isi_berita, url, judul, image_path, tgl_berita, tgl_update, tags_id, kategori_id, is_publish', 'safe', 'on'=>'search'),
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
			'kategori' => array(self::BELONGS_TO, 'Kategori', 'kategori_id'),
			'isPublish' => array(self::BELONGS_TO, 'StatusPublish', 'is_publish'),
			'tags' => array(self::BELONGS_TO, 'Tags', 'tags_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'berita_id' => 'Berita',
			'isi_berita' => 'Isi Berita',
			'url' => 'Url',
			'judul' => 'Judul',
			'image_path' => 'Image Path',
			'tgl_berita' => 'Tgl Berita',
			'tgl_update' => 'Tgl Update',
			'tags_id' => 'Tags',
			'kategori_id' => 'Kategori',
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

		$criteria->compare('berita_id',$this->berita_id,true);
		$criteria->compare('isi_berita',$this->isi_berita,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('judul',$this->judul,true);
		$criteria->compare('image_path',$this->image_path,true);
		$criteria->compare('tgl_berita',$this->tgl_berita,true);
		$criteria->compare('tgl_update',$this->tgl_update,true);
		$criteria->compare('tags_id',$this->tags_id,true);
		$criteria->compare('kategori_id',$this->kategori_id,true);
		$criteria->compare('is_publish',$this->is_publish);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Berita the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
