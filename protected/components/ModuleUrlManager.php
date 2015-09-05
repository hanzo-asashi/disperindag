<?php

/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/6/2015
 * Time: 12:48 AM
 */
class ModuleUrlManager
{
    static function collectRules()
    {
        if(!empty(Yii::app()->modules)){
            $cache = Yii::app()->getCache();
            foreach (Yii::app()->modules as $moduleName => $config ) {
                $urlRules = false;
                if($cache)
                    $urlRules = $cache->get('module.urls.'.$moduleName);
                if($urlRules===false){
                    $urlRules = array();
                    $module = Yii::app()->getModule($moduleName);
                    if(isset($module->urlRules))
                        $urlRules = $module->urlRules;
                    if($cache)
                        $cache->set('module.urls.'.$moduleName,$urlRules);
                }
                if(!empty($urlRules))
                    Yii::app()->getUrlManager()->addRules($urlRules);
            }
        }
        return true;
    }
}