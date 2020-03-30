<?php

namespace app\components;

use app\models\DB\Product;
use yii\base\Widget;

class ProductWidget extends Widget {

    public $products;
    public $pages;
    public $basketForm;

    public function run() {
        return $this->render('products',[
            'products'=>$this->products,
            'pages'=>$this->pages,
            'basketForm'=>$this->basketForm,
        ]);
    }

}
