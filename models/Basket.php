<?php


namespace app\models;


use app\models\DB\Order;
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
        $basket = Basket::getBasket();
        $product = Product::findOne($id);
        if (isset($basket['products'][$product->id])) { // такой товар уже есть?
            $count_current = $basket['products'][$product->id]['count'] + $count;
            $basket['products'][$product->id]['count'] = $count_current;
        } else { // такого товара еще нет
            $basket['products'][$product->id]['slug'] = $product->slug;
            $basket['products'][$product->id]['name'] = $product->name;
            $basket['products'][$product->id]['price'] = $product->price;
            $basket['products'][$product->id]['count'] = $count;
        }
        Basket::setBasket($basket);
    }

    /**
     * Метод удаляет товар из корзины
     */
    public static function removeFromBasket($slug){
        $id = Product::get($slug)->id;
        $basket = Basket::getBasket();
        unset($basket['products'][$id]);
        Basket::setBasket($basket);
    }

    /**
     * Метод удаляет все товары из корзины
     */
    public static function clearBasket() {
        Basket::setBasket(['products' => []]);
    }

    /**
     * Метод пересчитывает кооличество товаров в корзине
     * @param $products
     */
    public static function resetBasket($products){
        $basket = Basket::getBasket();
        foreach ($products as $product){
            foreach($product as $id=>$count) {
                if (isset($basket['products'][$id])) {
                    $basket['products'][$id]['count'] = $count;
                }
            }
        }
        Basket::setBasket($basket);
    }

    /**
     * Метод возвращает содержимое корзины
     */
    public static function getBasket() {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            Basket::setBasket(['products' => []]);
            return [];
        } else {
            return $session->get('basket');
        }
    }

    /*
     * Записываем кормзину из массвива, и заодно ее пишем пользователю
     */
    public static function setBasket($basket){
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
        $discount_price = self::discount($basket);
        $basket['discount_price'] = $discount_price;
        $session->set('basket', $basket);
        if($amount>0) $session->set('basketTitle', "Товаров в корзине: $amount, цена: $discount_price руб.");
        else $session->remove('basketTitle');
        Basket::setBasketToUser();
    }

    private static function discount($basket){
        $products = $basket['products'];
        uasort($products,['self','compareByPrice']);
        $i = 0;
        $product1 = [];
        foreach($products as $id=>$product){
            for($k=0;$k<$product['count'];$k++){
                $product1[] = $product;
            }
        }
        $price = 0;
        foreach($product1 as $i=>$product) {
            if ($i == 0) $price += $product['price'];
            else if ($i == 1) $price += $product['price']*0.9;
            else $price += $product['price']*0.8;
        }
        return $price;
    }

    private static function compareByPrice($a,$b){
        if ($a['price'] == $b['price']) {
            return 0;
        }
        return ($a['price'] > $b['price']) ? -1 : 1;
    }

     /**
     * Здесь записываем корзину в пользователя
     * @return bool
     */
    private static function setBasketToUser(){
        if (Yii::$app->user->getIsGuest()) {
            return false;
        }
        $basket = Basket::getBasket();
        $products = $basket['products'];
        $user = Yii::$app->user->identity;
        $user->basket = serialize($products);
        $user->save();
        return true;
    }

    /**
     * Как поймаем логин - тянем корзину из пользователя, если она там есть
     * @return bool
     */
    public static function getBasketFromUser(){
        if (Yii::$app->user->getIsGuest()) {
            return false;
        }
        $user = Yii::$app->user->identity;
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