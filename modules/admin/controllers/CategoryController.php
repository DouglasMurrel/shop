<?php

namespace app\modules\admin\controllers;

use app\models\DB\Category;
use yii\web\Controller;

class CategoryController extends DefaultController
{
    function actionIndex(){
        $tree = Category::getTree();
        return $this->render('index',[
            'tree' => $tree,
        ]);
    }
}