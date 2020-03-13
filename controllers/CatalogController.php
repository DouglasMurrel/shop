<?php

namespace app\controllers;

use app\models\DB\Brand;
use app\models\DB\Category;
use app\models\DB\Product;
use Yii;
use yii\web\Controller;

class CatalogController extends Controller{

    /**
     * Категория каталога товаров
     */
    public function actionCategory($slug) {
        $category = Category::get($slug);
        $products = $category->products;
        // товары категории
//        list($products, $pages) = $temp->getCategoryProducts($id);
        // данные о категории
        // устанавливаем мета-теги для страницы
        /*
        $this->setMetaTags(
            $category->name . ' | ' . Yii::$app->params['shopName'],
            $category->keywords,
            $category->description
        );
        return $this->render(
            'category',
            compact('category', 'products', 'pages')
        );
        */
        return print_r($products,1);
    }

    /**
     * Список всех брендов каталога товаров
     */
    public function actionBrands() {
        $brands = Brand::getAllBrands();
        return $this->render(
            'brands',
            compact('brands')
        );
    }

    /**
     * Список товаров бренда с идентификатором $slug
     */
    public function actionBrand($slug) {
        $brand = Brand::get($slug);
        $products = $brand->products;
        /*
        return $this->render(
            'brand',
            compact('brand')
        );
        */
        return print_r($products,1);
    }

    public function actionProduct($slug){
        $product = Product::get($slug);
        return print_r($product,1);
    }
}