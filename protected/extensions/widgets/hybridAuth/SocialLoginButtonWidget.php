<?php
class SocialLoginButtonWidget extends CWidget {
    
    public $type='button'; // options: button or icon
    public $buttonText='Signin with {provider}'; //
    public $htmlOptions=array(); // widget htmlOptions
    public $buttonHtmlOptions=array();  // individual button htmlOptions
    public $route; // route for processing hybrid auth
    public $params; // array of parameters (name=>value) that should be used instead of GET when generating button URL.
    public $paramVar='provider'; // name of the GET variable
    public $providers=array(); // array of providers
    public $enabled=true;
    
    private $_assetsUrl;
    
    /**
     * Initializes the widgets
     */
    public function init() {
        parent::init();
        
        if($this->_assetsUrl===null){
            $assetsDir=dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            $this->_assetsUrl=Yii::app()->assetManager->publish($assetsDir);
        }
    }

    /**
     * Execute the widgets
     */
    public function run() {
        if(!Yii::app()->user->isGuest || !$this->enabled) return ;
        
        Yii::beginProfile(get_class($this));
        
        Yii::app()->clientScript->registerCssFile($this->_assetsUrl.'/css/zocial.css');
        
        if(!isset($this->htmlOptions['class']))
            $this->htmlOptions['class']='social-signin';
        
        echo CHtml::openTag('div', $this->htmlOptions);
            foreach($this->providers as $provider){ 
                $this->buttonHtmlOptions['class']= implode(' ', array(
                    'zocial',
                    strtolower($provider),
                    $this->type,
                ));
                $this->params[$this->paramVar]=$provider;
                echo CHtml::link(
                        Yii::t('app',$this->buttonText, array('{provider}'=>$provider)),
                        Yii::app()->createUrl($this->route, $this->params),
                        $this->buttonHtmlOptions
                     );
            }
        echo CHtml::closeTag('div');

        Yii::endProfile(get_class($this));
    }

}//end class