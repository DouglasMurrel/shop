<?php


namespace app\controllers;


use app\models\DB\User;
use Yii;
use yii\web\Controller;

class UserController extends DefaultController
{
    function actionOrders(){
        $user = Yii::$app->user->identity;
        $orders = $user->fullOrdersInfo();
        return $this->render('index',[
            'orders'=>$orders,
        ]);
    }
}