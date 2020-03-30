<?php

namespace app\modules\admin\controllers;

use app\models\DB\User;
use app\models\DB\Order;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

class UserController extends DefaultController
{

    function actionIndex(){
        $list = User::userList();
        return $this->render('index', [
            'users' => $list['users'],
            'pages' => $list['pages'],
        ]);
    }

    function actionUser($id){
        $user = User::findOne($id);
        if($user===null) {
            throw new HttpException(
                404,
                'Запрошенная страница не найдена'
            );
        }
        $orders = $user->orders;

        return $this->render('user', [
            'user'=>$user,
            'orders' => $orders
        ]);
    }

    function actionAdmin(){
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            $id = intval(Yii::$app->request->post('id'));
            if($id!=Yii::$app->user->identity->id) {
                $value = intval(Yii::$app->request->post('value'));
                $user = User::findOne($id);
                if ($user) $user->setAdmin($value);
            }
        }
    }
}