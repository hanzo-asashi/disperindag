<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class BeritaController extends Controller{
    public function actionIndex(){
        $model = new Berita('search');
        if(isset($_GET['berita']))
            $model->attributes = $_GET['berita'];
        
        $params = array(
            'model'=>$model,
        );
        
        if(isset($_GET['ajax'])){
            $this->renderPartial("ajax", $params);            
        }else{
            $this->render("index", $params);
        }
    }
    
    public function actionDetail(){
        $this->render('detail');
    }
}