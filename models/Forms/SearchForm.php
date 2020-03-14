<?php

namespace app\models\Forms;

use app\models\DB\Image;
use app\models\DB\Product;
use Yii;
use yii\base\Model;
use yii\data\Pagination;

class SearchForm extends Model{
    public $query;
    public $page;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['query', 'filter','filter' => function ($search) {
                $search = iconv_substr($search, 0, 64);
                // удаляем все, кроме букв и цифр
                $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
                // сжимаем двойные пробелы
                $search = preg_replace('#\s+#u', ' ', $search);
                $search = trim($search);
                return $search;
            }],
            ['page','integer']
        ];
    }

    public function search(){
        if($this->validate()){
            $search = $this->query;
            $page = $this->page;
            if (empty($search)) {
                return [null, null];
            }

            // пробуем извлечь данные из кеша

            $data = Yii::$app->cache->getOrSet(['search','search'=>$search,'page'=>$page], function() use($search) {
                $query = Product::find()->where(['like', 'name', $search]);
                // постраничная навигация
                $pages = new Pagination([
                    'totalCount' => $query->count(),
                    'pageSize' => Yii::$app->params['pageSize'],
                    'forcePageParam' => false,
                    'pageSizeParam' => false
                ]);
                $products = $query
                    ->offset($pages->offset)
                    ->limit($pages->limit)
                    ->asArray()
                    ->all();
                foreach ($products as $k => $item) {
                    $item['image'] = Image::getFirst($item['id'], 'product');
                    $products[$k] = $item;
                }
                // сохраняем полученные данные в кеше
                $data = [$products, $pages];
                return $data;
            },60);

            return $data;
        }
        return '';
    }
}