<?php

namespace app\models\DB;

use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\HttpException;
use creocoder\nestedsets\NestedSetsBehavior;
use app\models\CategoryQuery;

/**
 * This is the model class for table "category".
 *
 * @property int $id id
 * @property string $slug Машинное имя
 * @property string $name Имя
 * @property string|null $keywords Мета-тег keywords
 * @property string|null $description Мета-тег description
 * @property string $lft
 * @property string $rgt
 * @property string $depth
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

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                // 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'name'], 'required'],
            [['parent_id','id'], 'integer'],
            [['slug', 'name', 'keywords', 'description'], 'string', 'max' => 255],
            [['slug'], 'unique','message'=>'Машинное имя занято'],
            [['id'], 'unique'],
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
        return $this->parents(1)->one();
    }

    /**
     * Возвращает родительские категории до корня
     */
    public function getParents(){
        $parentNodes = $this->parents()->all();
        $parents = [];
        $parents[] = $this;
        foreach ($parentNodes as $parent)$parents[] = $parent;
        $parents = array_reverse($parents);
        return $parents;
    }

    /**
     * Возвращает дочерние категории
     */
    public function getChildren() {
        // связь таблицы БД `category` с таблицей `category`
        return $this->children(1)->all();
    }

    public function getAllChildren() {
        // связь таблицы БД `category` с таблицей `category`
        return $this->children()->all();
    }

    public static function getTree(){
        $root = Category::findOne(1);
        if($root)return $root->getAllChildren();
        else return null;
    }

    /**
     * @param $id
     * @return void
     */
    public static function del($id){
        $node = Category::findOne($id);
        $left = $node->lft;
        $right = $node->rgt;
        Yii::$app->db->createCommand("DELETE FROM category WHERE lft >= $left AND rgt <= $right")->execute();
        Yii::$app->db->createCommand("UPDATE category SET lft = IF(lft > $left, lft - ($right - $left + 1), lft), 
            rgt = rgt - ($right - $left + 1) WHERE rgt > $right")->execute();
    }

    /**
     * Возвращает содержимое по слагу
     * @param string $slug
     */
    public static function get($slug){
        return Category::find()->where(['slug' => $slug])->one();
    }

    /**
     * Возвращает содержимое по имени
     * @param string $slug
     */
    public static function getbyName($name){
        return Category::find()->where(['name' => $name])->one();
    }

    /**
     * Возвращает содержимое по id
     * @param int $id
     */
    public static function getById($id){
        return Category::findOne($id);
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
            'pageSize' => Yii::$app->params['pageSize'], // кол-во товаров на странице
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
        $nodes = $this->children()->asArray()->all();
        $result = [];
        foreach ($nodes as $node){
            $result[] = $node['id'];
        }
        return $result;
    }

    public static function getCategoryFullData($slug){
        $data = Yii::$app->cache->getOrSet(['categoryProducts','slug'=>$slug,'page'=>Yii::$app->request->get('page')], function() use ($slug) {
            $category = Category::get($slug);
            if($category===null) {
                throw new HttpException(
                    404,
                    'Запрошенная страница не найдена'
                );
            }
            $products = $category->getCategoryProducts();
            $parents = $category->getParents();
            $links = [];
            foreach ($parents as $parent){
                if($parent->slug!='root') {
                    $link = ['label' => $parent->name, 'url' => ['catalog/category', 'slug' => $parent->slug]];
                    $links[] = $link;
                }
            }
            return [$category,$products,$links];
        },60);
        return $data;
    }

    public function __set($name,$value){
        if($name=='parent_id'){
            $parentNode = Category::findOne($value);
            if($parentNode)$this->appendTo($parentNode);
        }else parent::__set($name,$value);
    }

    public function __get($name)
    {
        if($name=='parent_id'){
            $parentNode = $this->getParent();
            if($parentNode)return $parentNode->id;else return 1;
        }
        return parent::__get($name);
    }
}
