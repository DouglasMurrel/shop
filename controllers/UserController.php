<?php


namespace app\controllers;


use app\models\DB\User;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    function actionOrders(){
        $user = User::findOne(Yii::$app->user->identity->getId());
        $orders = $user->fullOrdersInfo();
        return $this->render('index',[
            'orders'=>$orders,
        ]);
    }
}