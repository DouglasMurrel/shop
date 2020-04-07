<?php

namespace app\models\Forms;

use app\models\DB\Image;
use app\models\DB\Product;
use Stem\LinguaStemRu;
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
                $query = $this->getFromDB($search);
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

    /**
     * @param $search
     * @return \yii\db\ActiveQuery
     */
    private function getFromDB($search){
        $temp = explode(' ', $search);
        $words = [];
        $stemmer = new LinguaStemRu();
        foreach ($temp as $item) {
            if (iconv_strlen($item) > 3) {
                // получаем корень слова
                $words[] = $stemmer->stem_word($item);
            } else {
                $words[] = $item;
            }
        }
        // рассчитываем релевантность для каждого товара
        $relevance = "IF (`product`.`name` LIKE '%" . $words[0] . "%', 3, 0)";
        $relevance .= " + IF (`product`.`keywords` LIKE '%" . $words[0] . "%', 2, 0)";
        $relevance .= " + IF (`product`.`description` LIKE '%" . $words[0] . "%', 2, 0)";
        $relevance .= " + IF (`product`.`content` LIKE '%" . $words[0] . "%', 2, 0)";
        $relevance .= " + IF (`category`.`name` LIKE '%" . $words[0] . "%', 1, 0)";
        for ($i = 1; $i < count($words); $i++) {
            $relevance .= " + IF (`product`.`name` LIKE '%" . $words[$i] . "%', 3, 0)";
            $relevance .= " + IF (`product`.`keywords` LIKE '%" . $words[$i] . "%', 2, 0)";
            $relevance .= " + IF (`product`.`description` LIKE '%" . $words[$i] . "%', 2, 0)";
            $relevance .= " + IF (`product`.`content` LIKE '%" . $words[$i] . "%', 2, 0)";
            $relevance .= " + IF (`category`.`name` LIKE '%" . $words[$i] . "%', 1, 0)";
        }
        $query = Product::find()
            ->select([
                'id' => 'product.id',
                'name' => 'product.name',
                'slug' => 'product.slug',
                'price' => 'product.price',
                'content' => 'product.content',
                'relevance' => $relevance
            ])
            ->from('product')
            ->join('INNER JOIN', 'category', 'category.id = product.category_id')
            ->where(['like', 'product.name', $words[0]])
            ->orWhere(['like', 'product.keywords', $words[0]])
            ->orWhere(['like', 'product.description', $words[0]])
            ->orWhere(['like', 'product.content', $words[0]])
            ->orWhere(['like', 'category.name', $words[0]]);
        for ($i = 1; $i < count($words); $i++) {
            $query = $query->orWhere(['like', 'product.name', $words[$i]]);
            $query = $query->orWhere(['like', 'product.keywords', $words[$i]]);
            $query = $query->orWhere(['like', 'product.description', $words[0]]);
            $query = $query->orWhere(['like', 'product.content', $words[0]]);
            $query = $query->orWhere(['like', 'category.name', $words[$i]]);
        }
        $query = $query->orderBy(['relevance' => SORT_DESC]);

        // посмотрим, какой SQL-запрос был сформирован
        // print_r($query->createCommand()->getRawSql());
        return $query;
    }
}