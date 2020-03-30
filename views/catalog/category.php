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
        $img = '/images/category/' . $image;
        $file = Yii::getAlias('@webroot') . $img;
        if(file_exists($file) && $image!=''){
            echo Html::img(
                $img,
                ['alt' => $name, 'class' => 'img-responsive', 'style'=>'width:40px;height:40px;']
            );
        }
        ?>
    </p>
<?php
if (!empty($products)) {
    ?>
    <?= ProductWidget::widget(['products'=>$products,'pages'=>$pages,'basketForm'=>$basketForm]); ?>
<?php
} else {
    echo '<p>Нет товаров в этой категории</p>';
}
?>
</div>
