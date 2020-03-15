<?php


namespace app\controllers;


use app\models\Basket;
use Yii;
use yii\web\Controller;

class BasketController extends Controller{

    public function actionIndex() {
        $basket = (new Basket())->getBasket();
        return $this->render('index', ['basket' => $basket]);
    }

    public function actionAdd() {

        $basket = new Basket();

        /*
         * Данные должны приходить методом POST; если это не
         * так — просто показываем корзину
         */
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['basket/index']);
        }

        if($basket->load(Yii::$app->request->post())){

            // добавляем товар в корзину и перенаправляем покупателя
            // на страницу корзины
            $basket->addToBasket();
        }
        return $this->redirect(['basket/index']);
    }
}