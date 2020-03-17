<?php


namespace app\models;


use app\models\DB\Product;
use app\models\DB\User;
use Yii;
use yii\base\Model;
use yii\base\Event;

class Basket extends Model
{

    /**
     * Метод добавляет товар в корзину
     */
    public static function addToBasket($id, $count)
    {
        $session = Yii::$app->session;
        $session->open();
        $basket = Basket::getBasket();
        $product = Product::findOne($id);
        if (isset($basket['products'][$product->id])) { // такой товар уже есть?
            $count_current = $basket['products'][$product->id]['count'] + $count;
            $basket['products'][$product->id]['count'] = $count_current;
        } else { // такого товара еще нет
            $basket['products'][$product->id]['name'] = $product->name;
            $basket['products'][$product->id]['price'] = $product->price;
            $basket['products'][$product->id]['count'] = $count;
        }
        Basket::setBasket($basket);
    }

    /**
     * Метод удаляет товар из корзины
     */
    public static function removeFromBasket($id) {
        $id = abs((int)$id);
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            return;
        }
        $basket = $session->get('basket');
        if (!isset($basket['products'][$id])) {
            return;
        }
        unset($basket['products'][$id]);
        Basket::setBasket($basket);
    }

    /**
     * Метод возвращает содержимое корзины
     */
    public static function getBasket() {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            Basket::setBasket(['products'=>[]]);
            return [];
        } else {
            return $session->get('basket');
        }
    }

    /**
     * Метод удаляет все товары из корзины
     */
    public static function clearBasket() {
        Basket::setBasket(['products'=>[]]);
    }

    /*
     * Записываем кормзину из массвива, и заодно ее пишем пользователю
     */
    private static function setBasket($basket){
        $session = Yii::$app->session;
        $session->open();
        $price = 0.0;
        $amount = 0;
        foreach ($basket['products'] as $item) {
            $price = $price + $item['price'] * $item['count'];
            $amount += 1;
        }
        $basket['amount'] = $amount;
        $basket['price'] = $price;
        $session->set('basket', $basket);
        if($amount>0) $session->set('basketTitle', "Товаров в корзине: $amount, цена: $price руб.");
        Basket::setBasketToUser();
    }

     /**
     * Здесь записываем корзину в пользователя
     * @return bool
     */
    private static function setBasketToUser(){
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $basket = Basket::getBasket();
        $products = $basket['products'];
        $user = User::findOne(Yii::$app->user->identity->getId());
        $user->basket = serialize($products);
        $user->save();
        return true;
    }

    /**
     * Как поймаем логин - тянем корзину из пользователя, если она там есть
     * @return bool
     */
    public static function getBasketFromUser(){
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $user = User::findOne(Yii::$app->user->identity->getId());
        if($basket = unserialize($user->basket)) {
            if(!is_array($basket)){
                Basket::setBasket(['products' => []]);
                return false;
            }
            foreach ($basket as $k=>$v){
                $product = Product::findOne($k);
                if(!$product){
                    unset($basket[$k]);
                }
            }
            Basket::setBasket(['products' => $basket]);
            return true;
        }
        Basket::setBasket(['products' => []]);
        return false;
    }
}