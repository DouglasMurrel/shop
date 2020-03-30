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

        if(Yii::$app->request->post()) {
            if (!isset(Yii::$app->request->post('Category')['id'])) $category = new Category();
            else $category = Category::findOne(Yii::$app->request->post('Category')['id']);
            if ($category->load(Yii::$app->request->post()) && $category->validate()) {
                $category->save();
                Yii::$app->cache->flush();
                return $this->redirect([Url::to('index')]);
            }
        }

        return $this->render('index',[
            'tree' => $tree,
            'category'=>$category,
            'parent_id'=>$category->parent_id,
        ]);
    }

    function actionDelete(){
        $del = Yii::$app->request->post('del');
        foreach ($del as $id){
            $id = intval($id);
            Category::del($id);
        }
        Yii::$app->cache->flush();
        return $this->redirect(Url::to(['index']));
    }

    function actionCategory($id){
        $category = Category::findOne($id);
        if($category){
            $tree = Category::getTree();
            return $this->render('category',[
                'tree' => $tree,
                'category'=>$category,
                'parent_id'=>$category->parent_id,
            ]);
        }
        throw new \yii\web\NotFoundHttpException(
            404,
            'Запрошенная страница не найдена'
        );
    }
}