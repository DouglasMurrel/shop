<?php

namespace app\models\DB;

use Yii;
use yii\web\HttpException;

/**
 * This is the model class for table "product".
 *
 * @property int $id id
 * @property string $slug Машинное имя
 * @property int|null $category_id Категория
 * @property int|null $brand_id Бренд
 * @property string $name Имя
 * @property string|null $content Описание
 * @property float $price Цена
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 * @property int $hit Лидер продаж
 * @property int $new Новый
 * @property int $sale Распродажа
 *
 * @property Brand $brand
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'name'], 'required'],
            [['category_id', 'brand_id', 'hit', 'new', 'sale'], 'integer'],
            [['price'], 'number'],
            [['slug', 'name', 'content', 'keywords', 'description'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'category_id' => 'Категория',
            'brand_id' => 'Бренд',
            'name' => 'Имя',
            'content' => 'Описание',
            'price' => 'Цена',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'hit' => 'Лидер продаж',
            'new' => 'Новый',
            'sale' => 'Распродажа',
        ];
    }

    /**
     * Gets query for [[Brand]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public static function hitProducts()
    {
        $arrResult = Yii::$app->cache->getOrSet('hitProducts', function() {
            $arrResult = Product::find()->where(['hit' => 1])->limit(3)->asArray()->all();
            foreach ($arrResult as $k => $item) {
                $item['image'] = Image::getFirst($item['id'], 'product');
                $arrResult[$k] = $item;
            }
            return $arrResult;
        });
        return $arrResult;
    }

    public static function newProducts()
    {
        $arrResult = Yii::$app->cache->getOrSet('newProducts', function() {
            $arrResult = Product::find()->where(['new' => 1])->limit(3)->asArray()->all();
            foreach ($arrResult as $k => $item) {
                $item['image'] = Image::getFirst($item['id'], 'product');
                $arrResult[$k] = $item;
            }
            return $arrResult;
        });
        return $arrResult;
    }

    public static function saleProducts()
    {
        $arrResult = Yii::$app->cache->getOrSet('saleProducts', function() {
            $arrResult = Product::find()->where(['sale' => 1])->limit(3)->asArray()->all();
            foreach ($arrResult as $k => $item) {
                $item['image'] = Image::getFirst($item['id'], 'product');
                $arrResult[$k] = $item;
            }
            return $arrResult;
        });
        return $arrResult;
    }

    public function firstImage()
    {
        return Image::getFirst($this->id, 'product');
    }

    public function images()
    {
        return Image::get($this->id, 'product');
    }

    /**
     * Возвращает содержимое по слагу
     * @param string $slug
     */
    public static function get($slug)
    {
        return Product::find()->where(['slug' => $slug])->one();
    }

    /**
     * Возвращаем похожие товары (из той же категории того же бренда)
     */
    public function getSimilar()
    {
        $slug = $this->slug;
        $category_id = $this->category_id;
        $brand_id = $this->brand_id;
        $id = $this->id;
        $similar = Yii::$app->cache->getOrSet(['similar','slug'=>$slug], function() use ($category_id,$brand_id,$id) {
            $arrResult = Product::find()
                ->where([
                    'category_id' => $category_id,
                    'brand_id' => $brand_id
                ])
                ->andWhere(['NOT IN', 'id', $id])
                ->limit(3)
                ->asArray()
                ->all();
            foreach ($arrResult as $k => $item) {
                $item['image'] = Image::getFirst($item['id'], 'product');
                $arrResult[$k] = $item;
            }
            return $arrResult;
        },60);
        return $similar;
    }

    public static function getProductFullData($slug){
        $data = Yii::$app->cache->getOrSet(['product','slug'=>$slug], function() use ($slug) {
            $product = Product::get($slug);
            if($product===null) {
                throw new HttpException(
                    404,
                    'Запрошенная страница не найдена'
                );
            }
            $brand = Brand::getById($product->brand_id);
            $category = Category::getById($product->category_id);
            $parents = $category->getParents();
            $links = [];
            foreach ($parents as $parent){
                $link = ['label'=>$parent->name,'url'=>['catalog/category','slug'=>$parent->slug]];
                $links[] = $link;
            }
            $link = ['label'=>$product->name,'url'=>['catalog/product','slug'=>$slug]];
            $links[] = $link;
            $images = $product->images();
            return [$product, $brand, $images, $links];
        },60);
        return $data;
    }
}
