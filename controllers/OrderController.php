<?php

namespace app\controllers;

use app\models\Basket;
use app\models\DB\Order;
use app\models\DB\User;
use app\models\Forms\RegisterForm;
use Yii;
use yii\helpers\Url;

class OrderController extends DefaultController
{
    public function actionCheckout()
    {
        $order = new Order();
        if ($order->load(Yii::$app->request->post())) {
            $validFlag = false;
            if ($order->validate()) {
                $password = null;
                if (Yii::$app->user->isGuest) {
                    $user = User::find()->where(['email'=>$order->email])->one();
                    if(!$user){
                        $registerModel = new RegisterForm();
                        $password = $this->rand_string(8);
                        if(filter_var($order->email,FILTER_VALIDATE_EMAIL))$email = $order->email;
                        else $email = $order->phone.'@phone';
                        $user = $registerModel->register_user($email, $password);
                    }
                    if($user){
                        $validFlag = true;
                    }
                } else {
                    $validFlag = true;
                    $password = null;
                    $user = Yii::$app->user->identity;
                }
                if($validFlag) {
                    $content = Basket::getBasket();
                    $order->user_id = $user->id;
                    $order->amount = $content['price'];
                    $order->save();
                    $order->addItems($content);
                    Basket::clearBasket();

                    if(!preg_match('/@phone$/',$user->email)) {
                        Yii::$app->mailer->compose('order', ['order' => $order, 'password' => $password, 'site' => Url::base(true)])
                            ->setTo($order->email)
                            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                            ->setSubject('Ваш заказ')
                            ->send();
                    }

                    Yii::$app->mailer->compose('new_order',['order' => $order])
                        ->setTo(Yii::$app->params['senderEmail'])
                        ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                        ->setSubject('Новый заказ')
                        ->send();

                    Yii::$app->session->addFlash('success','Заказ успешно оформлен! Сведения о заказе отправлены на вашу электронную почту');
                }
            }
            return $this->redirect(Url::to('/'));
        }
        if(Yii::$app->user->isGuest){
            $email = '';
            $phone = '';
        }else{
            $email = Yii::$app->user->identity->email;
            $phone = Yii::$app->user->identity->phone;
        }
        return $this->render('checkout', [
            'order'=>$order,
            'links'=>[],
            'email'=>$email,
            'phone'=>$phone,
        ]);
    }

    private function rand_string( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }

}
