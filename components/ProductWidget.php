<?php
namespace app\components;

use app\models\Basket;
use Yii;
use yii\base\Widget;

/**
 * Виджет для вывода товара
 */
class ProductWidget extends Widget {

    public $product;

    public function run() {
        $basketForm = new Basket();
        // сохраняем полученные данные в кеше
        $html = Yii::$app->cache->getOrSet('widget-product'.$this->product['slug'], function() use ($basketForm) {
            return $this->render('product', ['product' => $this->product, 'basketForm'=>$basketForm]);
        }, 60);
        return $html;
    }

}
