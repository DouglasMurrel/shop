<?php

namespace app\controllers;

use app\models\DB\Category;
use Yii;
use yii\web\Controller;

class CatalogController extends Controller{

    public function actionCategory(){
        $slug = Yii::$app->request->get('slug');
        return print_r(Category::get($slug),1);
    }
}