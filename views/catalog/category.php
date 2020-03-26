<?php

use app\components\ProductWidget;
use app\components\SearchWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\LinkPager;

$this->params['breadcrumbs'] = $links;

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');

?>
<?= SearchWidget::widget(); ?>
<div class="col-sm-12">
<h1><?=$name?></h1>
    <p>
        <?
        $img = '/images/category/' . $image['image'];
        $file = Yii::getAlias('@webroot') . $img;
        if(file_exists($file) && $image['image']!=''){
            echo Html::img(
                $img,
                ['alt' => $name, 'class' => 'img-responsive', 'style'=>'width:40px;height:40px;']
            );
        }
        ?>
    </p>
<p><?=$content?></p>
<?php
if (!empty($products)) {
    ?>
<div class="row mb-2">
<?
    foreach ($products as $product) {
?>
        <div class="col-sm-4 p-0">
            <?= ProductWidget::widget(['product'=>$product]); ?>
        </div>
<?php
    }
?>
</div>
    <div class="ml-n3">
        <?= LinkPager::widget(['pagination' => $pages,'lastPageLabel'=>true,'firstPageLabel'=>true,'maxButtonCount'=>4]); /* постраничная навигация */ ?>
    </div>
<?php
} else {
    echo '<p>Нет товаров в этой категории</p>';
}
?>
</div>
