<?php

namespace app\models\DB;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

/**
 * This is the model class for table "brand".
 *
 * @property int $id id
 * @property string $slug Машинное имя
 * @property string $name Имя
 * @property string|null $content Описание
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 *
 * @property Product[] $products
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'name'], 'required'],
            [['slug', 'name', 'content', 'keywords', 'description'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'slug' => 'Машинное имя',
            'name' => 'Имя',
            'content' => 'Описание',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id'])->inverseOf('brand');
    }

    /**
     * Возвращает массив товаров бренда
     */
    public function getBrandProducts() {
        $arrResult = $this->products;
        $cnt = count($arrResult);
        $pages = new Pagination([
            'totalCount' => $cnt,
            'pageSize' => 10, // кол-во товаров на странице
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);
        $arrResult = array_slice($arrResult,$pages->offset,$pages->limit);
        foreach($arrResult as $k=>$item){
            $item = $item->toArray();
            $item['image'] = Image::getFirst($item['id'],'product');
            $arrResult[$k] = $item;
        }
        return ['products'=>$arrResult,'pages'=>$pages];
    }

    /**
     * Возвращает массив всех брендов каталога и
     * количество товаров для каждого бренда
     */
    public static function getAllBrands() {
        $brands = Yii::$app->cache->getOrSet('brands', function() {
            $query = self::find();
            $brands = $query
                ->select([
                    'id' => 'brand.id',
                    'name' => 'brand.name',
                    'slug' => 'brand.slug',
                    'content' => 'brand.content',
                    'count' => 'COUNT(*)'
                ])
                ->innerJoin(
                    'product',
                    'product.brand_id = brand.id'
                )
                ->groupBy([
                    'brand.id', 'brand.name', 'brand.content'
                ])
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();
            return $brands;
        },60);
        return $brands;
    }

    /**
     * Возвращает массив популярных брендов и
     * количество товаров для каждого бренда
     */
    public static function getPopularBrands() {
        // получаем бренды с наибольшим кол-вом товаров
        $brands = self::find()
            ->select([
                'id' => 'brand.id',
                'name' => 'brand.name',
                'slug' => 'brand.slug',
                'count' => 'COUNT(*)'
            ])
            ->innerJoin(
                'product',
                'product.brand_id = brand.id'
            )
            ->groupBy([
                'brand.id', 'brand.name'
            ])
            ->orderBy(['count' => SORT_DESC])
            ->limit(Yii::$app->params['pageSize'])
            ->asArray()
            // для дальнейшей сортировки
            ->indexBy('name')
            ->all();
        // теперь нужно отсортировать бренды по названию
        ksort($brands);
        return $brands;
    }

    /**
     * Возвращает содержимое по слагу
     * @param string $slug
     */
    public static function get($slug){
        return Brand::find()->where(['slug' => $slug])->one();
    }

    /**
     * Возвращает содержимое по id
     * @param int $id
     */
    public static function getById($id){
        return Brand::findOne($id);
    }

    /**
     * Возвращает первое изображение
     */
    public function getFirstImage(){
        return Image::getFirst($this->id,'brand');
    }

    public static function getBrandFullData($slug){
        $data = Yii::$app->cache->getOrSet(['brandProducts','slug'=>$slug,'page'=>Yii::$app->request->get('page')], function() use ($slug) {
            $brand = Brand::get($slug);
            if($brand===null) {
                throw new HttpException(
                    404,
                    'Запрошенная страница не найдена'
                );
            }
            return [$brand,$brand->getBrandProducts()];
        },60);
        return $data;
    }
}
