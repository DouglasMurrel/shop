<?php

namespace app\modules\admin\controllers;

use app\models\DB\Order;
use app\models\DB\OrderItem;
use Yii;
use yii\helpers\Url;

class OrdersController extends DefaultController
{

    function actionIndex(){
        $list = Order::orderList();
        return $this->render('index', [
            'orders' => $list['orders'],
            'pages' => $list['pages'],
        ]);
    }

    function actionOrder($id){
        $order = Order::findOne($id);
        $orderItems = $order->orderItems;

        if(OrderItem::loadMultiple($orderItems, Yii::$app->request->post()) && OrderItem::validateMultiple($orderItems)) {
            if ($order->load(Yii::$app->request->post()) && $order->validate()) {
                $sum = 0;
                foreach($orderItems as $orderItem){
                    $cost = $orderItem->price*$orderItem->quantity;
                    $sum += $cost;
                    $orderItem->cost = $cost;
                    $orderItem->save();
                }
                $order->amount = $sum;
                $order->updated = date('Y-m-d H:i:s');
                Yii::info(Yii::$app->request->post());
                Yii::info($order->status);
                $order->save();
                Yii::$app->session->addFlash('success','Заказ успешно сохранен!');
                return $this->redirect(Url::to(['orders/order','id'=>$order->id]));
            }
        }

        return $this->render('order', ['order' => $order,'orderItems'=>$orderItems]);
    }
}
