<?php

namespace app\models\DB;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int $id id
 * @property string $slug Машинное имя
 * @property int $parent_id Родительская категория
 * @property string $name Имя
 * @property string|null $content Описание
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 * @property string|null $image Картинка
 *
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'name'], 'required'],
            [['parent_id'], 'integer'],
            [['slug', 'name', 'content', 'keywords', 'description', 'image'], 'string', 'max' => 255],
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
            'parent_id' => 'Родительская категория',
            'name' => 'Имя',
            'content' => 'Описание',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'image' => 'Картинка',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @param string $slug
     * @return ActiveRecord
     */
    public static function get($slug){
        return Category::find()->where(['slug'=>$slug])->one();
    }
}
