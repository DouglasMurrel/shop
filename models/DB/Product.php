<?php

namespace app\models\DB;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id id
 * @property int|null $category_id Категория
 * @property int $brand_id Бренд
 * @property string $name Имя
 * @property string|null $content Описание
 * @property float $price Цена
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 * @property string|null $image Картинка
 * @property int $hit Лидер продаж
 * @property int $new Новый
 * @property int $sale Распродажа
 *
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
            [['category_id', 'brand_id', 'hit', 'new', 'sale'], 'integer'],
            [['name'], 'required'],
            [['price'], 'number'],
            [['name', 'content', 'keywords', 'description', 'image'], 'string', 'max' => 255],
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
            'category_id' => 'Категория',
            'brand_id' => 'Бренд',
            'name' => 'Имя',
            'content' => 'Описание',
            'price' => 'Цена',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'image' => 'Картинка',
            'hit' => 'Лидер продаж',
            'new' => 'Новый',
            'sale' => 'Распродажа',
        ];
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
}