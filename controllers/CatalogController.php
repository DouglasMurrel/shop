<?php

namespace app\controllers;

use app\models\DB\Brand;
use app\models\DB\Category;
use app\models\DB\Product;
use app\models\Basket;
use app\models\Forms\SearchForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

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
        $newProducts = Product::newProducts();
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
        $data = Category::getCategoryFullData($slug);
        list($category,$products,$links) = $data;
        // товары категории
        return $this->render(
            'category',
            [
                'products'=>$products['products'],
                'links'=>$links,
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
        $brands = Brand::getAllBrands();
        return $this->render(
            'brands',
            [
                'brands' => $brands,
                'links'=>[['label'=>'Все бренды','url'=>['catalog/brands']]],
            ]
        );
    }

    /**
     * Список товаров бренда с идентификатором $slug
     */
    public function actionBrand($slug) {
        $data = Brand::getBrandFullData($slug);
        list($brand,$products) = $data;
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
                'links'=>[['label'=>$brand->name,'url'=>['catalog/brand','slug'=>$slug]]],
            ]
        );
    }

    /**
     * Страница товара с идентификатором $slug
     */
    public function actionProduct($slug) {
        $data = Product::getProductFullData($slug);
        list($product, $brand, $images, $links) = $data;
        // получаем товары, похожие на текущий
        $similar = $product->getSimilar($slug);
        $basketForm = new Basket();
        return $this->render(
            'product',
            [
                'product'=>$product,
                'brand'=>$brand,
                'images'=>$images,
                'similar'=>$similar,
                'links'=>$links,
                'basketForm'=>$basketForm,
            ]
        );
    }

    /**
     * Поиск
     */
    public function actionSearch(){
        $modelSearch = new SearchForm();
        if($modelSearch->load(['SearchForm'=>Yii::$app->request->get()])) {
            $data = $modelSearch->search();
            list($products, $pages) = $data;
            return $this->render(
                'search',
                [
                    'products'=>$products,
                    'pages'=>$pages,
                ]
            );
        }
        return $this->redirect(['/']);
    }
}