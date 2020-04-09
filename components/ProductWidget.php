<?php

namespace app\components;

use app\models\DB\Product;
use Yii;
use yii\base\Widget;

class ProductWidget extends Widget {

    public $products;
    public $pages;
    public $basketForm;

    public function run() {
        $indexFlag = false;
        if(Yii::$app->controller->id == 'catalog' && Yii::$app->controller->action->id == 'index'){
            $indexFlag = true;
        }
        return $this->render('products',[
            'products'=>$this->products,
            'pages'=>$this->pages,
            'basketForm'=>$this->basketForm,
            'indexFlag'=>$indexFlag,
        ]);
    }

}
