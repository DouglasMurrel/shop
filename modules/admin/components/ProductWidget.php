<?php

namespace app\modules\admin\components;

use app\models\DB\Color;
use yii\base\Widget;

class ProductWidget extends Widget {

    public $product;
    public $tree;

    public function run() {
        $colors = $this->product->colors;
        $allColors = Color::getAll();
        $color = new Color();
        return $this->render('product',[
            'product'=>$this->product,
            'tree'=>$this->tree,
            'colors'=>$colors,
            'allColors'=>$allColors,
            'color'=>$color,
        ]);
    }

}
