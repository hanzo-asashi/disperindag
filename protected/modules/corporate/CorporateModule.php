<?php

class CorporateModule extends CWebModule
{
	// getAssetsUrl()
    //    return the URL for this module's assets, performing the publish operation
    //    the first time, and caching the result for subsequent use.
    
    private $_assetsUrl;

	public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('xxii.assets') );
        return $this->_assetsUrl;
    }
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application


        // We can configure our module depending on the value
        // of Yii::app()->endName.
		//$this->frontend = (yii::app()->endName == 'frontend') ? 'bar1' : 'bar2';

		yii::app()->onModuleCreate(new CEvent($this));

        // import the module-level models and components
		$this->setImport(array(
			'corporate.models.*',
			'corporate.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
