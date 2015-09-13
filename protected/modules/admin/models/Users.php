<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $userid
 * @property string $username
 * @property string $password
 * @property string $namalengkap
 * @property string $email
 * @property string $token
 * @property string $salt
 * @property integer $is_register
 * @property integer $is_aktif
 * @property string $created_date
 * @property string $updated_date
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, updated_date', 'required'),
			array('is_register, is_aktif', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>100),
			array('password, namalengkap', 'length', 'max'=>150),
			array('email', 'length', 'max'=>30),
			array('token, salt', 'length', 'max'=>255),
			array('created_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('userid, username, password, namalengkap, email, token, salt, is_register, is_aktif, created_date, updated_date', 'safe', 'on'=>'search'),
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
			'userid' => 'User ID',
			'username' => 'Username',
			'password' => 'Password',
			'namalengkap' => 'Nama Lengkap',
			'email' => 'Email',
			'token' => 'Token',
			'salt' => 'Salt',
			'is_register' => 'Is Register',
			'is_aktif' => 'Aktif',
			'created_date' => 'Tgl Daftar',
			'updated_date' => 'Tgl Update',
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

		$criteria->compare('userid',$this->userid,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('namalengkap',$this->namalengkap,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('is_register',$this->is_register);
		$criteria->compare('is_aktif',$this->is_aktif);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_date',$this->updated_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getName($user_id)
	{
		if ($user_id) {
			$nama = User::model()->findByAttributes(array('userid'=>$user_id));
			return $nama['username'];
		}
	}

	public function beforeSave(){
		if($this->isNewRecord){
			$this->created_date = Date('Y-m-d H:i:s');
			$this->is_register = 1;
			$this->is_aktif = 0;
			// $this->username = $this->email;
		}
		else{
			$this->updated_date = Date('Y-m-d H:i:s');
		}

		$salt = openssl_random_pseudo_bytes(22);
		$salt = '$2a$%13$' . strtr(base64_encode($salt), array('_' => '.', '~' => '/'));
		$this->password = crypt($this->password, $salt);

		// $this->password = crypt($this->password);

		return parent::beforeSave();
	}

	public function validatePassword($password){
		// var_dump($this->password);
		return  crypt($password, $this->password) == $this->password;
	}

	public function getEmail($user_id)
	{
		if ($user_id) {
			$email = User::model()->findByAttributes(array('userid'=>$user_id));
			return $email['email'];
		}
	}

	public function getStatus($status)
	{
		switch ($status) {
			case '1':
				$st = "<span class='label label-primary'>Aktif</span>";
				break;

			case '0':
				$st = "<span class='label label-danger'>Tidak Aktif</span>";
				break;

			default:
				$st = "";
				break;
		}
		return $st;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
