<?php

namespace app\models\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
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
            [['slug', 'name', 'code', 'corpus', 'parameters','price'], 'required'],
            [['category_id'], 'integer'],
            [['price'], 'number'],
            [['slug', 'name', 'keywords', 'description', 'code', 'corpus', 'parameters'], 'string', 'max' => 255],
            [['slug'], 'unique','message'=>'Имя уже используется'],
            [['name'], 'unique','message'=>'Машинное имя уже используется'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['xlsFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xls, xlsx'],
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
        $images = Image::get($id,'product');
        foreach($images as $image)Image::findOne($image['id'])->delete();
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
//        Yii::$app->db->createCommand("delete from product")->execute();
//        Yii::$app->db->createCommand("alter table product AUTO_INCREMENT=1")->execute();
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $row = [];
            foreach ($cellIterator as $cell) {
                $row[] = $cell->getValue();
            }
            $product = new Product();
            $product->name = $row[0];
            $product->price = $row[1];
            $product->code = $row[2];
            $product->corpus = $row[3];
            $product->parameters = $row[4];
            $product->slug = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$product->name);
            $category = Category::getbyName($row[5]);
            if($category)$product->category_id = $category->id;
            $product->save();

            $image_file = $row[6];
            if($image_file!=''){
                $image = new Image();
                $image->entity_id = $product->id;
                $image->entity_type = 'product';
                $image->sort = 1;
                $image->image = $image_file;
                $image->save();
            }
        }
        return true;
    }
}
