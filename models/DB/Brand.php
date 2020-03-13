<?php

namespace app\models\DB;

use Yii;

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
     * Возвращает массив всех брендов каталога и
     * количество товаров для каждого бренда
     */
    public static function getAllBrands() {
        $query = self::find();
        $brands = $query
            ->select([
                'id' => 'brand.id',
                'name' => 'brand.name',
                'slug' => 'brand.slug',
                'content' => 'brand.content',
                'image' => 'brand.image',
                'count' => 'COUNT(*)'
            ])
            ->innerJoin(
                'product',
                'product.brand_id = brand.id'
            )
            ->groupBy([
                'brand.id', 'brand.name', 'brand.content', 'brand.image'
            ])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();
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
            ->limit(10)
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
}
