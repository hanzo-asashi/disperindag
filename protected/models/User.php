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
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
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
			array('username', 'length', 'max'=>100, 'min' => 5, 'message'=>"Username Salah (Panjang antara 5 dan 100 karakters)" ),
			array('password', 'length', 'max'=>100, 'min' => 4, 'message'=>"Password salah (minimal panjang simbol adalah 4 karakters)" ),
			array('username', 'unique', 'message'=>'Pengguna ini sudah terdaftar'),
			array('email', 'unique', 'message'=>'Email ini sudah terdaftar'),
			array('is_aktif', 'in', 'range' => array(self::STATUS_ACTIVE,self::STATUS_NOACTIVE)),
			array('is_register', 'in', 'range' => array(0,1)),
			array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
			array('updated_date', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
			array('username', 'match', 'pattern'=>'/^[A-Za-z0-9_]+$/u','message' => "Simbol Salah (A-z0-9)."),
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
			'userid' => 'Userid',
			'username' => 'Username',
			'password' => 'Password',
			'verifyPassword'=>'Retype Password',
			'namalengkap' => 'Namalengkap',
			'email' => 'Email',
			'token' => 'Token',
			'salt' => 'Salt',
			'is_register' => 'Is Register',
			'is_aktif' => 'Is Aktif',
			'created_date' => 'Created Date',
			'updated_date' => 'Updated Date',
		);
	}

	public function scopes()
	{
		return array(
			'active'=>array(
				'condition'=>'status='.self::STATUS_ACTIVE,
			),
			'notactive'=>array(
				'condition'=>'status='.self::STATUS_NOACTIVE,
			),
			'notsafe'=>array(
				'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status',
			),
		);
	}

	public function defaultScope()
	{
		return CMap::mergeArray($this->defaultScope,array(
			'alias'=>'user',
			'select' => 'user.id, user.username, user.email, user.create_at, user.lastvisit_at, user.superuser, user.status',
		));
	}

	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'UserStatus' => array(
				self::STATUS_NOACTIVE => 'Tidak Aktif',
				self::STATUS_ACTIVE => 'Aktif',
			),
			'AdminStatus' => array(
				'0' => 'Tidak',
				'1' => 'Ya',
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
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
			'pagination'=>array(
				'pageSize'=>Yii::app()->getModule('user')->user_page_size,
			),
		));
	}
	public function getCreatetime() {
		return strtotime($this->created_date);
	}

	public function setCreatetime($value) {
		$this->create_at=date('Y-m-d H:i:s',$value);
	}

	public function getLastvisit() {
		return strtotime($this->lastvisit_at);
	}

	public function setLastvisit($value) {
		$this->updated_date=date('Y-m-d H:i:s',$value);
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
