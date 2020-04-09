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
        $postFlag = false;
        if ($order->load(Yii::$app->request->post())) {
            $postFlag = true;
            $validFlag = false;
            if ($order->validate()) {
                $password = null;
                if (Yii::$app->user->isGuest) {
                    $user = User::registerByPhone($order->email,$order->phone,$password);
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
                    $order->discount = $content['discount_price'];
                    $order->save();
                    $order->addItems($content);
                    Basket::clearBasket();

                    if($user->hasEmail()) {
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

                    if($user->hasEmail()) {
                        Yii::$app->session->addFlash('success', 'Заказ успешно оформлен! Сведения о заказе отправлены на вашу электронную почту. Оператор свяжется с вами по телефону');
                    }else{
                        Yii::$app->session->addFlash('success', 'Заказ успешно оформлен!  Оператор свяжется с вами по телефону');
                    }
                }
                return $this->redirect(Url::to('/'));
            }
        }
        if(Yii::$app->user->isGuest){
            $email = '';
            $phone = '';
            $name = '';
            $address = '';
            $lastname = '';
            $oname = '';
            $area = '';
            $city = '';
            $zipcode = '';
        }else{
            $email = Yii::$app->user->identity->email;
            $lastOrder = Yii::$app->user->identity->lastOrder();
            if(!$postFlag && $lastOrder) {
                $phone = $lastOrder->phone;
                $name = $lastOrder->name;
                $lastname = $lastOrder->lastname;
                $oname = $lastOrder->oname;
                $area = $lastOrder->area;
                $city = $lastOrder->city;
                $zipcode = $lastOrder->zipcode;
                $address = $lastOrder->address;
            }else if($postFlag){
                $phone = $order->phone;
                $name = $order->name;
                $lastname = $order->lastname;
                $oname = $order->oname;
                $area = $order->area;
                $city = $order->city;
                $zipcode = $order->zipcode;
                $address = $order->address;
            }else{
                $phone = '';
                $name = '';
                $address = '';
                $lastname = '';
                $oname = '';
                $area = '';
                $city = '';
                $zipcode = '';
            }
        }
        return $this->render('checkout', [
            'order'=>$order,
            'links'=>[],
            'email'=>$email,
            'phone'=>$phone,
            'name'=>$name,
            'address'=>$address,
            'lastname'=> $lastname,
            'oname'=>$oname,
            'area'=>$area,
            'city'=>$city,
            'zipcode'=>$zipcode,
        ]);
    }

}
