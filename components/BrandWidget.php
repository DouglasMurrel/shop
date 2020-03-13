<?php
namespace app\components;

use Yii;
use yii\base\Widget;
use app\models\DB\Brand;

/**
 * Виджет для вывода списка брендов каталога
 */
class BrandWidget extends Widget {

    public function run() {
        // сохраняем полученные данные в кеше
        $html = Yii::$app->cache->getOrSet('widget-brands', function(){
            $brands = Brand::getPopularBrands();
            return $this->render('brands', ['brands' => $brands]);
        }, 60);
        return $html;
    }

}