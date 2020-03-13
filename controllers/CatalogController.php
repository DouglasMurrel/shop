<?php

namespace app\controllers;

use app\models\DB\Brand;
use app\models\DB\Category;
use app\models\DB\Product;
use Yii;
use yii\web\Controller;

class CatalogController extends Controller{

    public function beforeAction($event)
    {
        Yii::$app->view->title = Yii::$app->params['defaultTitle'];
        Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['defaultDescription']],'description');
        Yii::$app->view->registerMetaTag(['name' => 'keywords','content' => Yii::$app->params['defaultKeywords']],'keywords');
        return parent::beforeAction($event);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // получаем лидеров продаж
        $hitProducts = Product::hitProducts();
        // получаем новые товары
        $newProducts = Product::newPoducts();
        // получаем товары распродажи
        $saleProducts = Product::saleProducts();
        return $this->render('index',[
            'hitProducts'=>$hitProducts,
            'newProducts'=>$newProducts,
            'saleProducts'=>$saleProducts,
        ]);
    }

    /**
     * Категория каталога товаров
     */
    public function actionCategory($slug) {
        $category = Category::get($slug);
        $products = $category->getCategoryProducts();
        // товары категории
        return $this->render(
            'category',
            [
                'products'=>$products,
                'name'=>$category->name,
                'content'=>$category->content,
                'description'=>$category->description,
                'keywords'=>$category->keywords,
                'image'=>$category->getFirstImage(),
            ]
        );
    }

    /**
     * Список всех брендов каталога товаров
     */
    public function actionBrands() {
        $brands = Brand::getAllBrands();
        return $this->render(
            'brands',
            [
                'brands' => $brands,
            ]
        );
    }

    /**
     * Список товаров бренда с идентификатором $slug
     */
    public function actionBrand($slug) {
        $brand = Brand::get($slug);
        $products = $brand->getBrandProducts();
        return $this->render(
            'brand',
            [
                'products'=>$products,
                'name'=>$brand->name,
                'content'=>$brand->content,
                'description'=>$brand->description,
                'keywords'=>$brand->keywords,
                'image'=>$brand->getFirstImage(),
            ]
        );
    }

    public function actionProduct($slug){
        $product = Product::get($slug);
        return print_r($product,1);
    }
}