<?php

/**
 * This is the model class for table "corp_providers".
 *
 * The followings are the available columns in table 'corp_providers':
 * @property integer $provider_id
 * @property integer $kode_lelang
 * @property string $nama_tender
 * @property string $announce_date
 * @property string $nilai_hps
 * @property string $nilai_pagu
 * @property string $jenis_kontrak
 * @property string $syarat_kualifikasi
 * @property string $tahap_lelang
 * @property string $save_draft
 * @property string $publish
 */
class Corporate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'corp_providers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('provider_id, kode_lelang, nama_tender, announce_date, nilai_hps, nilai_pagu, jenis_kontrak, syarat_kualifikasi, tahap_lelang, save_draft', 'required'),
			array('provider_id, kode_lelang', 'numerical', 'integerOnly'=>true),
			array('nama_tender, jenis_kontrak, syarat_kualifikasi, tahap_lelang', 'length', 'max'=>255),
			array('nilai_hps, nilai_pagu', 'length', 'max'=>10),
			array('save_draft, publish', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('provider_id, kode_lelang, nama_tender, announce_date, nilai_hps, nilai_pagu, jenis_kontrak, syarat_kualifikasi, tahap_lelang, save_draft, publish', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'provider_id' => 'Provider',
			'kode_lelang' => 'Kode Lelang',
			'nama_tender' => 'Nama Tender',
			'announce_date' => 'Announce Date',
			'nilai_hps' => 'Nilai Hps',
			'nilai_pagu' => 'Nilai Pagu',
			'jenis_kontrak' => 'Jenis Kontrak',
			'syarat_kualifikasi' => 'Syarat Kualifikasi',
			'tahap_lelang' => 'Tahap Lelang',
			'save_draft' => 'Save Draft',
			'publish' => 'Publish',
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

		//$criteria->compare('provider_id',$this->provider_id);
		$criteria->compare('kode_lelang',$this->kode_lelang);
		$criteria->compare('nama_tender',$this->nama_tender,true);
		$criteria->compare('announce_date',$this->announce_date,true);
		$criteria->compare('nilai_hps',$this->nilai_hps,true);
		$criteria->compare('nilai_pagu',$this->nilai_pagu,true);
		$criteria->compare('jenis_kontrak',$this->jenis_kontrak,true);
		$criteria->compare('syarat_kualifikasi',$this->syarat_kualifikasi,true);
		$criteria->compare('tahap_lelang',$this->tahap_lelang,true);
		$criteria->compare('save_draft',$this->save_draft,true);
		$criteria->compare('publish',$this->publish,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Corporate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
