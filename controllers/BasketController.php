<?php


namespace app\controllers;


use app\models\Basket;
use app\models\Forms\BasketForm;
use Yii;
use yii\web\Controller;

class BasketController extends Controller{

    public function actionIndex() {
        $basket = Basket::getBasket();
        return $this->render('index', ['basket' => $basket]);
    }

    public function actionAdd() {

        $basket = new BasketForm();

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
            Yii::$app->session->addFlash('success','Товар успешно добавлен в корзину');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}