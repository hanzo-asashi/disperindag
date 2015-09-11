<?php

    class UserController extends Controller
    {
        /**
         * @var CActiveRecord the currently loaded data model instance.
         */
        private $_model;

        /**
         * @return array action filters
         */
        public function filters()
        {
            return CMap::mergeArray(parent::filters(), array(
                'accessControl', // perform access control for CRUD operations
            ));
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         * @return array access control rules
         */
        public function accessRules()
        {
            return array(
                array(
                    'allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view', 'registration'),
                    'users'   => array('*'),
                ),
                array(
                    'deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        /**
         * Displays a particular model.
         */
        public function actionView()
        {
            $model = $this->loadModel();
            $this->render('view', array(
                'model' => $model,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         */
        public function loadModel()
        {
            if ($this->_model === null) {
                if (isset($_GET['id'])) {
                    $this->_model = User::model()->findbyPk($_GET['id']);
                }
                if ($this->_model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            }

            return $this->_model;
        }

        /**
         * Lists all models.
         */
        public function actionIndex()
        {
            $dataProvider = new CActiveDataProvider('User', array(
                'criteria'   => array(
                    'condition' => 'status>' . User::STATUS_BANNED,
                ),
                'pagination' => array(
                    'pageSize' => Yii::app()->controller->module->user_page_size,
                ),
            ));

            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         *
         * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
         *
         * @throws
         * @return array
         */
        public function loadUser($id = null)
        {
            if ($this->_model === null) {
                if ($id !== null || isset($_GET['id'])) {
                    $this->_model = User::model()->findbyPk($id !== null ? $id : $_GET['id']);
                }
                if ($this->_model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            }

            return $this->_model;
        }

        public function actionRegistration()
        {
            //$this->redirect(array('/admin/registration'));
            //var_dump("test");exit;
            $model = new RegistrationForm();
            $profile = new Profile;
            $profile->regMode = true;
            //var_dump($model);exit;
            // ajax validator
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
                echo UActiveForm::validate(array($model, $profile));
                Yii::app()->end();
            }

            //var_dump($_POST['ajax']);exit;

            if (Yii::app()->user->id) {
                $this->redirect(Yii::app()->controller->module->profileUrl);
            } else {
                if (isset($_POST['RegistrationForm'])) {
                    //var_dump($_POST['RegistrationForm']);exit;
                    $model->attributes = $_POST['RegistrationForm'];
                    $profile->attributes = ((isset($_POST['Profile']) ? $_POST['Profile'] : array()));
                    if ($model->validate() && $profile->validate()) {
                        $soucePassword = $model->password;
                        $model->activkey = AdminModule::encrypting(microtime() . $model->password);
                        $model->password = AdminModule::encrypting($model->password);
                        $model->verifyPassword = AdminModule::encrypting($model->verifyPassword);
                        $model->superuser = 0;
                        $model->status = ((Yii::app()->controller->module->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);

                        if ($model->save()) {
                            $profile->user_id = $model->id;
                            $profile->save();
                            if (Yii::app()->controller->module->sendActivationMail) {
                                $activation_url = $this->createAbsoluteUrl('/admin/user/activation/activation',
                                    array("activkey" => $model->activkey, "email" => $model->email));
                                AdminModule::sendMail($model->email, AdminModule::t("Anda registrasi dari {site_name}",
                                    array('{site_name}' => Yii::app()->name)),
                                    AdminModule::t("Silahkan Aktifkan akun anda melalui link {activation_url}",
                                        array('{activation_url}' => $activation_url)));
                            }

                            if ((Yii::app()->controller->module->loginNotActiv || (Yii::app()->controller->module->activeAfterRegister && Yii::app()->controller->module->sendActivationMail == false)) && Yii::app()->controller->module->autoLogin) {
                                $identity = new UserIdentity($model->username, $soucePassword);
                                $identity->authenticate();
                                Yii::app()->user->login($identity, 0);
                                $this->redirect(Yii::app()->controller->module->returnUrl);
                            } else {
                                if (!Yii::app()->controller->module->activeAfterRegister && !Yii::app()->controller->module->sendActivationMail) {
                                    Yii::app()->user->setFlash('registration',
                                        AdminModule::t("Terima kasih anda sudah mendaftar. Hubungi Admin untuk mengaktifkan akun anda."));
                                } elseif (Yii::app()->controller->module->activeAfterRegister && Yii::app()->controller->module->sendActivationMail == false) {
                                    Yii::app()->user->setFlash('registration',
                                        AdminModule::t("Terima kasih anda sudah mendaftar. Silahkan {{login}}.", array(
                                            '{{login}}' => CHtml::link(AdminModule::t('Login'),
                                                Yii::app()->controller->module->loginUrl),
                                        )));
                                } elseif (Yii::app()->controller->module->loginNotActiv) {
                                    Yii::app()->user->setFlash('registration',
                                        AdminModule::t("Terima kasih anda sudah mendaftar. Silahkan cek email anda atau login."));
                                } else {
                                    Yii::app()->user->setFlash('registration',
                                        AdminModule::t("Terima kasih anda sudah mendaftar. Silahkan cek email anda."));
                                }
                                $this->refresh();
                            }
                        }
                    } else {
                        $profile->validate();
                    }
                }
                $this->render('/user/registration', array('model' => $model, 'profile' => $profile));
            }
        }
    }
