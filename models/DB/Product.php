<?php

namespace app\models\DB;

use Yii;
use yii\bootstrap4\ActiveField;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int $id id
 * @property string $slug Машинное имя
 * @property int|null $category_id Категория
 * @property string $name Имя
 * @property string|null $content Описание
 * @property float $price Цена
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 * @property int $hit Лидер продаж
 * @property int $new Новый
 * @property int $sale Распродажа
 * @property string $code Код
 * @property string $corpus Корпус
 * @property string $parameters Параметры
 *
 * @property OrderItem[] $orderItems
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFile;

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
            [['slug', 'name', 'code', 'corpus', 'parameters','price'], 'required'],
            [['category_id'], 'integer'],
            [['price'], 'number'],
            [['slug', 'name', 'keywords', 'description', 'code', 'corpus', 'parameters'], 'string', 'max' => 255],
            [['slug'], 'unique','message'=>'Имя уже используется'],
            [['name'], 'unique','message'=>'Машинное имя уже используется'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
            'name' => 'Имя',
            'price' => 'Цена',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'code' => 'Код',
            'corpus' => 'Корпус',
            'parameters' => 'Параметры',
        ];
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
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
     * Возвращает первое изображение
     */
    public function getFirstImage(){
        return Image::getFirst($this->id,'product');
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
            $category = Category::getById($product->category_id);
            $parents = $category->getParents();
            $links = [];
            foreach ($parents as $parent){
                if($parent->slug!='root') {
                    $link = ['label' => $parent->name, 'url' => ['catalog/category', 'slug' => $parent->slug]];
                    $links[] = $link;
                }
            }
            $link = ['label'=>$product->name,'url'=>['catalog/product','slug'=>$slug]];
            $links[] = $link;
            $image = $product->getFirstImage();
            return [$product, $image, $links];
        },60);
        return $data;
    }

    public static function productList(){
        $query = Product::find();
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => Yii::$app->params['pageSize'], // кол-во товаров на странице
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);
        $arrResult = $query
            ->orderBy('name')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return ['products'=>$arrResult,'pages'=>$pages];
    }

    /**
     * @param $id
     * @return void
     */
    public static function del($id){
        $node = Product::findOne($id);
        $node->delete();
    }

    public function saveImage(){
        if ($this->validate()) {
            $filename = $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(Yii::$app->basePath.'/web/images/product/' . $filename);
            $image = Image::find()->where(['entity_id'=>$this->id,'entity_type'=>'product'])->one();
            if(!$image){
                $image = new Image();
                $image->entity_id = $this->id;
                $image->entity_type = 'product';
                $image->sort = 1;
            }
            $image->image = $filename;
            return $image->save();
        } else {
            return false;
        }
    }
}
