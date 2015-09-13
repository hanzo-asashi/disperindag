<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    class BeritaController extends Controller
    {
        public function actionIndex()
        {

            $dataProvider = new CActiveDataProvider('berita', array(
                'pagination' => array(
                    'pageSize' => Yii::app()->controller->module->user_page_size,
                ),
            ));

            $this->render('index', array(
                'dataProvider' => $dataProvider,
            ));
        }

        public function actionGetKategori(){

        }

        public function actionGetTags(){

        }

        public function actionCreate()
        {
            $request = Yii::app()->request->getIsPostRequest();
            $ajaxRequest = Yii::app()->request->getIsAjaxRequest();

            if($request){
                $berita = new Berita();
                $kategori = new Kategori();
                $tags = new Tags();
                $image = new Image();

                $postBerita = !empty($_POST['Berita']) ? $_POST['Berita'] : "";
                $postKategori = !empty($_POST['Kategori']) ? $_POST['Kategori'] : "";
                $postTags = !empty($_POST['Tags']) ? $_POST['Tags'] : "";

                if($kategori){
                    $kategori->setAttributes($postKategori);
                }

                if($tags){
                    $tags->setAttributes($postTags);
                }

                if($postBerita){
                    $berita->setAttributes($postBerita);
                    $berita->setCreatetime($postBerita['tgl_create']);
                    $berita->setUpdatetime($postBerita['tgl_update']);

                }
            }
            $jsonData = array();

            if($ajaxRequest){
                echo CJSON::encode($jsonData);
                Yii::app()->end();
            }else{
                $this->render("create", array());
            }
        }

        public function actionUpdate()
        {
            $this->render("edit", array());
        }

        public function actionDraft()
        {
            $this->render("draft", array());
        }

        public function actionArchive()
        {
            $this->render("archive", array());
        }

    }