<?php

namespace app\modules\admin\components;

use yii\base\Widget;

class CategoryWidget extends Widget {

    public $category;
    public $tree;
    public $parent_id;

    public function run() {
        return $this->render('category',[
            'category'=>$this->category,
            'tree'=>$this->tree,
            'parent_id'=>$this->parent_id,
        ]);
    }

}