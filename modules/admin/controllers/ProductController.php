<?php

namespace app\modules\admin\controllers;

use app\models\DB\Category;
use app\models\DB\Product;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class ProductController extends DefaultController
{
    function actionIndex(){
        $tree = Category::getTree();
        $products = Product::productList();
        $product = new Product();

        if(Yii::$app->request->post()) {
            if (!isset(Yii::$app->request->post('Product')['id'])) $product = new Product();
            else $product = Product::findOne(Yii::$app->request->post('Product')['id']);
            if ($product->load(Yii::$app->request->post()) && $product->validate()) {
                $product->save();
                $product->imageFile = UploadedFile::getInstance($product,'imageFile');
                if($product->imageFile){
                    $product->saveImage();
                }
                return $this->redirect([Url::to('index')]);
            }
            Yii::$app->cache->flush();
        }

        return $this->render('index',[
            'tree' => $tree,
            'products' => $products,
            'product'=>$product,
        ]);
    }

    function actionDelete(){
        $del = Yii::$app->request->post('del');
        foreach ($del as $id){
            $id = intval($id);
            Product::del($id);
        }
        Yii::$app->cache->flush();
        return $this->redirect(Url::to(['index']));
    }

    function actionProduct($id){
        $product = Product::findOne($id);
        if($product){
            $tree = Category::getTree();
            return $this->render('product',[
                'tree' => $tree,
                'product'=>$product,
            ]);
        }
        throw new \yii\web\NotFoundHttpException();
    }
}