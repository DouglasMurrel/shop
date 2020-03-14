<?php

namespace app\models\DB;

use Yii;
use yii\data\Pagination;

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
            'parent_id' => 'Родительская категория',
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
        return $this->hasMany(Product::className(), ['category_id' => 'id'])->inverseOf('category');
    }

    /**
     * Возвращает родительскую категорию
     */
    public function getParent() {
        // связь таблицы БД `category` с таблицей `category`
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * Возвращает дочерние категории
     */
    public function getChildren() {
        // связь таблицы БД `category` с таблицей `category`
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    /**
     * Возвращает содержимое по слагу
     * @param string $slug
     */
    public static function get($slug){
        return Category::find()->where(['slug' => $slug])->one();
    }

    /**
     * Возвращает первое изображение
     */
    public function getFirstImage(){
        return Image::getFirst($this->id,'category');
    }

    /**
     * Возвращает массив товаров в категории и во
     * всех ее потомках, т.е. в дочерних, дочерних-дочерних и так далее
     */
    public function getCategoryProducts() {
        // получаем массив идентификаторов всех потомков категории
        $ids = $this->getAllChildrenIds($this->id);
        $ids[] = $this->id;
        $query = Product::find()->where(['in', 'category_id', $ids]);
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 10, // кол-во товаров на странице
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);
        $arrResult = $query->offset($pages->offset)
                    ->limit($pages->limit)
                    ->asArray()
                    ->all();
        foreach($arrResult as $k=>$item){
            $item['image'] = Image::getFirst($item['id'],'product');
            $arrResult[$k] = $item;
        }
        return ['products'=>$arrResult,'pages'=>$pages];
    }

    /**
     * Возвращает массив идентификаторов всех потомков категории $id,
     * т.е. дочерние, дочерние дочерних и так далее
     */
    protected function getAllChildrenIds($id) {
        $children = [];
        $ids = $this->getChildrenIds($id);
        foreach ($ids as $item) {
            $children[] = $item;
            $c = $this->getAllChildrenIds($item);
            foreach ($c as $v) {
                $children[] = $v;
            }
        }
        return $children;
    }

    /**
     * Возвращает массив идентификаторов дочерних категорий (прямых
     * потомков) категории с уникальным идентификатором $id
     */
    protected function getChildrenIds($id) {
        $children = self::find()->where(['parent_id' => $id])->asArray()->all();
        $ids = [];
        foreach ($children as $child) {
            $ids[] = $child['id'];
        }
        return $ids;
    }
}
