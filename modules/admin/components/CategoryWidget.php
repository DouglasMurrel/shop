<?php

namespace app\modules\admin\components;

use yii\base\Widget;

class CategoryWidget extends Widget {

    public $category;
    public $tree;

    public function run() {
        return $this->render('category',[
            'category'=>$this->category,
            'tree'=>$this->tree,
            'parent_id'=>isset($this->category->parents(1)->one()->id)?$this->category->parents(1)->one()->id:1,
        ]);
    }

}