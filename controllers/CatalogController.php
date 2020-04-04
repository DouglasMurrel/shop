<?php

namespace app\controllers;

use app\models\DB\Category;
use app\models\DB\Product;
use app\models\Forms\BasketForm;
use app\models\Forms\SearchForm;
use Yii;
use yii\web\Controller;

class CatalogController extends DefaultController{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Категория каталога товаров
     */
    public function actionCategory($slug) {
        $data = Category::getCategoryFullData($slug);
        list($category,$products,$links) = $data;
        // товары категории
        $basketForm = new BasketForm();
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
                'basketForm' => $basketForm,
            ]
        );
    }

    /**
     * Страница товара с идентификатором $slug
     */
    public function actionProduct($slug) {
        $data = Product::getProductFullData($slug);
        list($product, $images, $links) = $data;
        $basketForm = new BasketForm();
        return $this->render(
            'product',
            [
                'product'=>$product,
                'images'=>$images,
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
            $basketForm = new BasketForm();
            return $this->render(
                'search',
                [
                    'products'=>$products,
                    'pages'=>$pages,
                    'basketForm' => $basketForm,
                ]
            );
        }
        return $this->redirect(['/']);
    }
}