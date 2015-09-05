<?php

class AdminModule extends CWebModule {

    //public $layout = '//layouts/column1-admin';
    public $urlRules = array(
        'admin' =>'admin/default/index',
    );
    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        //$this->layout = "/layouts/partials/main-admin";
        
        $this->setImport(array(
            'admin.models.*',
            'application.models.*',
            'admin.components.*',
        ));
    }

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('admin.assets'));

        return $this->_assetsUrl;
    }   

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $controller->layout = '//layouts/main-admin';
            return true;
        } else
            return false;
    }

}
