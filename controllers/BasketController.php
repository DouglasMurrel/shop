<?php


namespace app\controllers;


use app\models\Basket;
use app\models\Forms\BasketForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class BasketController extends DefaultController{

    public function actionIndex() {
        $basket = Basket::getBasket();
        $basketForms = [];
        if(isset($basket['products']))$cnt = count($basket['products']);else $cnt=0;
        for($i=0;$i<$cnt;$i++)$basketForms[] = new BasketForm();

        if(BasketForm::loadMultiple($basketForms, Yii::$app->request->post()) && BasketForm::validateMultiple($basketForms)) {
            $products = [];
            foreach($basketForms as $basketForm)$products[] = [$basketForm->id => $basketForm->count];
            Basket::resetBasket($products);
            return $this->redirect(Url::to(['index']));
        }

        return $this->render('index', ['basket' => $basket,'basketForms'=>$basketForms]);
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

        if($basket->load(Yii::$app->request->post()) && $basket->validate()){

            // добавляем товар в корзину и перенаправляем покупателя
            // на страницу корзины
            Basket::addToBasket($basket->id,$basket->count);
            Yii::$app->session->addFlash('success','Товар успешно добавлен в корзину');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClear() {
        Basket::clearBasket();
        return $this->redirect(['basket/index']);
    }

    public function actionRemove($slug){
        Basket::removeFromBasket($slug);
        return $this->redirect(['basket/index']);
    }
}