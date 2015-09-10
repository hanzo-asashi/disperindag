<?php

/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/9/2015
 * Time: 10:35 PM
 */
class RegistrasiController extends Controller
{
	public $defaultAction = 'registration';
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	/**
	 * Registrasi Pengguna
	 */
	public function actionIndex(){
		$model = new User;
		$pesan = "";

		$this->performAjaxValidation($model);

		if(Yii::app()->user->id){
			$this->redirect('/login');
		}else{
			if (isset($_POST['User'])) {
				$model->attributes = $_POST['User'];
				if($model->validate()){
					$model->username = $_POST['User']['username'];
					$model->password = md5($_POST['User']['password']);
					$model->namalengkap = $_POST['User']['namalengkap'];
					$token = Yii::app()->request->getCsrfToken();
					$salt = md5(($_POST['User']['username']));
					$model->token = $token;
					$model->salt = $salt;
					$save = $model->save();

					$existUser = User::model()->findByAttributes(array('email'=>$_POST['email']));
					if($existUser->email == $_POST['email']){
						Yii::app()->user->setFlash('Sukses',"Data berhasil disimpan.");
					}

					if ($save) {
						//$this->redirect('/admin/user');
						Yii::app()->user->setFlash('Sukses',"Data berhasil disimpan.");
						$pesan = "Sukses";
					}else{
						Yii::app()->user->setFlash('Gagal',"Data Gagal disimpan.");
						$pesan = "Gagal";
					}
				}

			}
			$this->render('index', array(
				'model' => $model,
				'pesan' => $pesan,
			));
		}
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}