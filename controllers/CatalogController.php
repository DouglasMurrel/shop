<?php

namespace app\controllers;

use app\models\DB\Brand;
use app\models\DB\Category;
use app\models\DB\Product;
use app\models\Forms\BasketForm;
use app\models\Forms\SearchForm;
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
     * Страница товара с идентификатором $slug
     */
    public function actionProduct($slug) {
        $data = Product::getProductFullData($slug);
        list($product, $image, $links) = $data;
        $basketForm = new BasketForm();
        return $this->render(
            'product',
            [
                'product'=>$product,
                'image'=>$image,
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