<?php

namespace app\modules\admin\components;

use yii\base\Widget;

class ProductWidget extends Widget {

    public $product;
    public $tree;

    public function run() {
        return $this->render('product',[
            'product'=>$this->product,
            'tree'=>$this->tree,
        ]);
    }

}
