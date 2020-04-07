<?php

namespace app\models\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\bootstrap4\ActiveField;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int $id id
 * @property string $slug Машинное имя
 * @property int|null $category_id Категория
 * @property string $name Имя
 * @property float $price Цена
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 * @property string|null $content Описание
 *
 * @property OrderItem[] $orderItems
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFile;
    public $xlsFile;

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
            [['slug', 'name', 'price'], 'required'],
            [['category_id'], 'integer'],
            [['price'], 'number'],
            [['slug', 'name', 'keywords', 'description' ], 'string', 'max' => 255],
            ['content','string'],
            [['name'], 'unique','message'=>'Имя уже используется'],
            [['slug'], 'unique','message'=>'Машинное имя уже используется'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 0],
            [['xlsFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xls, xlsx'],
            ['firstpage', 'boolean'],
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
            'content' => 'Описание',
            'firstpage' => 'Показывать на главной',
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
     * Возвращает содержимое по имени
     * @param string $name
     */
    public static function getByName($name)
    {
        return Product::find()->where(['name' => $name])->one();
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
            $images = $product->images();
            return [$product, $images, $links];
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
        $images = Image::get($id,'product');
        foreach($images as $image)Image::findOne($image['id'])->delete();
    }

    public static function truncate(){
        Product::deleteAll();
        Image::deleteAll(['entity_type'=>'product']);
    }

    public function saveImage(){
        if ($this->validate()) {
            $q = new Query();
            $maxSort = $q->select("max(sort)")->from("image")->where(['entity_id'=>$this->id,'entity_type'=>'product'])->one();
            $maxSort = intval($maxSort);
            foreach ($this->imageFile as $file){
                $filename = $file->baseName . '.' . $file->extension;
                $file->saveAs(Yii::$app->basePath.'/web/images/product/' . $filename);
                $image = new Image();
                $maxSort++;
                $image->entity_id = $this->id;
                $image->entity_type = 'product';
                $image->sort = $maxSort;
                $image->image = $filename;
                $image->save();
            }
        } else {
            return false;
        }
    }

    /**
     * @param UploadedFile $file
     */
    public static function loadFromXls($file){
        $filename = $file->tempName;
        $inputFileType = IOFactory::identify($filename);
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filename);
        $worksheet = $spreadsheet->getActiveSheet();
        $used_rows = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $row = [];
            foreach ($cellIterator as $cell) {
                $row[] = $cell->getValue();
            }
            $product = Product::getByName($row[0]);
            if(!$product)$product = new Product();
            $product->name = $row[0];
            $product->price = $row[1];
            $product->slug = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$product->name);
            $category = Category::getbyName($row[5]);
            if($category)$product->category_id = $category->id;
            if($product->save()) {
                $used_rows[] = $product->id;
/*
                $image_file = $row[6];
                if ($image_file != '') {
                    $image = new Image();
                    $image->entity_id = $product->id;
                    $image->entity_type = 'product';
                    $image->sort = 1;
                    $image->image = $image_file;
                    $image->save();
                }
*/
            }
        }
        $ids = implode(',',$used_rows);
        Product::deleteAll(['not in','id',$ids]);
        Image::deleteAll(['and',['not in','entity_id',$ids],"entity_type='product'"]);
        return true;
    }

    public static function firstPageProducts(){
        $data = Yii::$app->cache->getOrSet(['firstPageProducts'], function() {
            $products = Product::find()->where(['firstpage' => 1])->asArray()->all();
            foreach($products as $i=>$product){
                $image = Image::getFirst($product['id'],'product');
                $product['image'] = $image;
                $products[$i] = $product;
            }
            return $products;
        });
        return $data;
    }
}
