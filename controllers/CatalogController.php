<?php

namespace app\controllers;

use app\models\DB\Brand;
use app\models\DB\Category;
use app\models\DB\Product;
use Yii;
use yii\helpers\Url;
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
        $hitProducts = Yii::$app->cache->getOrSet('hitProducts', function() {
            return Product::hitProducts();
        },60);
        // получаем новые товары
        $newProducts = Yii::$app->cache->getOrSet('newProducts', function() {
            return Product::newProducts();
        },60);
        // получаем товары распродажи
        $saleProducts = Yii::$app->cache->getOrSet('saleProducts', function() {
            return Product::saleProducts();
        },60);
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
        $products = Yii::$app->cache->getOrSet(['categoryProducts','slug'=>$slug,'page'=>Yii::$app->request->get('page')], function() use ($category) {
            return $category->getCategoryProducts();
        },60);
        // товары категории
        return $this->render(
            'category',
            [
                'products'=>$products['products'],
                'pages'=>$products['pages'],
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
        $brands = Yii::$app->cache->getOrSet('brands', function(){
            return Brand::getAllBrands();
        },60);
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
        $products = Yii::$app->cache->getOrSet(['brandProducts','slug'=>$slug,'page'=>Yii::$app->request->get('page')], function() use ($brand) {
            return $brand->getBrandProducts();
        },60);
        return $this->render(
            'brand',
            [
                'products'=>$products['products'],
                'pages'=>$products['pages'],
                'name'=>$brand->name,
                'content'=>$brand->content,
                'description'=>$brand->description,
                'keywords'=>$brand->keywords,
                'image'=>$brand->getFirstImage(),
            ]
        );
    }

    /**
     * Страница товара с идентификатором $slug
     */
    public function actionProduct($slug) {
        // пробуем извлечь данные из кеша
        $data = Yii::$app->cache->getOrSet(['product','slug'=>$slug], function() use ($slug) {
            $product = Product::get($slug);
            $brand = Brand::getById($product->brand_id);
            $category = Category::getById($product->category_id);
            $parents = $category->getParents();
            $links = [];
            foreach ($parents as $parent){
                $link = ['label'=>$parent->name,'url'=>['catalog/category','slug'=>$parent->slug]];
                $links[] = $link;
            }
            $link = ['label'=>$product->name,'url'=>['catalog/product','slug'=>$slug]];
            $links[] = $link;
            $images = $product->images();
            return [$product, $brand, $images, $links];
        },60);
        list($product, $brand, $images, $links) = $data;
        // получаем товары, похожие на текущий
        $similar = Yii::$app->cache->getOrSet(['similar','slug'=>$slug], function() use ($product) {
            return $product->getSimilar();
        },60);
        return $this->render(
            'product',
            [
                'product'=>$product,
                'brand'=>$brand,
                'images'=>$images,
                'similar'=>$similar,
                'links'=>$links,
            ]
        );
    }
}