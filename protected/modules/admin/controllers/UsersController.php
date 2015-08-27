<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class UsersController extends Controller{
    public function actionIndex(){
        $this->render("index", array(
            
        ));
    }
    
    public function actionView(){
        
    }
    public function actionCreate(){
        $this->render("create", array(
            
        ));
    }
}

