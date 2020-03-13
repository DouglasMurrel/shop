<?php
namespace app\components;

use Yii;
use yii\base\Widget;

/**
 * Виджет для вывода товара
 */
class ProductWidget extends Widget {

    public $product;

    public function run() {
        // сохраняем полученные данные в кеше
        $html = Yii::$app->cache->getOrSet('widget-product'.$this->product['slug'], function(){
            return $this->render('product', ['product' => $this->product]);
        }, 60);
        return $html;
    }

}
