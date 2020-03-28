<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;

class ProductController extends DefaultController
{
    function actionIndex(){
        return $this->render('index');
    }
}