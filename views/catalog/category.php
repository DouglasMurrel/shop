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
<?=$content?>
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
