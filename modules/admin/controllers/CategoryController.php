<?php

namespace app\modules\admin\controllers;

use app\models\DB\Category;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class CategoryController extends DefaultController
{
    function actionIndex(){
        $tree = Category::getTree();
        $category = new Category();
        return $this->render('index',[
            'tree' => $tree,
            'category'=>$category,
        ]);
    }

    function actionDelete(){
        $del = Yii::$app->request->post('del');
        foreach ($del as $id){
            $id = intval($id);
            Category::del($id);
        }
        return $this->redirect(Url::to(['category/index']));
    }

    function actionSave(){
        if(!Yii::$app->request->post('id'))$category = new Category();
        else $category = Category::findOne(Yii::$app->request->post('id'));
        if ($category->load(Yii::$app->request->post()) && $category->validate()) {
            $category->save();
        }
        return $this->redirect(Url::to(['category/index']));
    }
}