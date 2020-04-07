<?php


namespace app\components;

use yii\base\Widget;
use app\models\DB\Category;
use Yii;
use yii\helpers\Url;

class TreeWidget extends Widget
{
    public function run() {
        $slug = '';
        if(Yii::$app->requestedRoute == 'catalog/category' && Yii::$app->request->get('slug')!=''){
            $slug = Yii::$app->request->get('slug');
        }
        $html = Yii::$app->cache->getOrSet(['catalog-menu', $slug], function() use ($slug){
            $openCategories = [];
            if($slug!='') {
                $currentCategory = Category::get($slug);
                if ($currentCategory) {
                    $openParents = $currentCategory->getParents();
                    foreach ($openParents as $parent) {
                        if ($parent->id != $currentCategory->id) $openCategories[] = $parent->id;
                    }
                }
            }
            $root = Category::find()->where(['id'=>1])->asArray()->one();
            $html = '';
            if($root) {
                $root = $this->makeTree($root);
                $tree = $root['nodes'];
                if (!empty($tree)) {
                    $html = $this->render('menu', ['tree' => $tree, 'openCategories' => $openCategories, 'slug'=>$slug]);
                } else {
                    $html = '';
                }
            }
            return $html;
        }, 60);
        return $html;
    }

    private function makeTree($node){
        $nodeObj = Category::findOne($node['id']);
        $nodes = $nodeObj->children(1)->asArray()->all();
        if(count($nodes)>0) {
            $nodes_array = [];
            foreach ($nodes as $newNode) {
                $nodes_array[] = $this->makeTree($newNode);
            }
            $node['nodes'] = $nodes_array;
        }
        $node['image'] = $nodeObj->getFirstImage();
        return $node;
    }
}