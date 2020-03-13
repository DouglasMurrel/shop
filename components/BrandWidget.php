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
        // пробуем извлечь данные из кеша
        $html = Yii::$app->cache->get('widget-brands');
        if ($html === false) {
            // данных нет в кеше, получаем их заново
            $brands = Brand::getPopularBrands();
            $html = $this->render('brands', ['brands' => $brands]);
            // сохраняем полученные данные в кеше
            Yii::$app->cache->set('widget-brands', $html);
        }
        return $html;
    }

}