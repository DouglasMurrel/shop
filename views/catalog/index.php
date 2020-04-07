<?php

/* @var $this yii\web\View */

use app\components\ProductWidget;
use app\components\SearchWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [['label'=>'','template'=>'']];

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>
<?= SearchWidget::widget(); ?>
<div class="col-lg-6">
    <h2>Каталог</h2>
    <div class="category-products container pl-0">
        <?= TreeWidget::widget(); ?>
    </div>
</div>
<div class="col-lg-6">
    <?
    if (!empty($products)) {
    ?>
        <?= ProductWidget::widget(['products'=>$products,'basketForm'=>$basketForm]); ?>
    <?php
    }
    ?>
</div>



