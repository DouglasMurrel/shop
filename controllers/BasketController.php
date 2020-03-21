<?php


namespace app\controllers;


use app\models\Basket;
use app\models\Forms\BasketForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class BasketController extends Controller{

    public function beforeAction($event)
    {
        Yii::$app->view->title = Yii::$app->params['defaultTitle'];
        Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['defaultDescription']],'description');
        Yii::$app->view->registerMetaTag(['name' => 'keywords','content' => Yii::$app->params['defaultKeywords']],'keywords');
        return parent::beforeAction($event);
    }

    public function actionIndex() {
        $basket = Basket::getBasket();
        $basketForms = [];
        $cnt = count($basket['products']);
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