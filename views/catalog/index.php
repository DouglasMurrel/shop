<?php

/* @var $this yii\web\View */

use app\components\BrandWidget;
use app\components\ProductWidget;
use app\components\SearchWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [['label'=>'']];

if($name!='')$this->title = $name;
if($description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if($keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>
<?= SearchWidget::widget(); ?>
<div class="col-sm-3">
    <h2>Каталог</h2>
    <div class="category-products">
        <?= TreeWidget::widget(); ?>
    </div>

    <h2><a href="<?= Url::to(['catalog/brands']); ?>" class='text-nowrap'>Бренды</a></h2>
    <div class="brand-products">
        <?= BrandWidget::widget(); ?>
    </div>
</div>
<div class="col-sm-9">
    <?php if (!empty($hitProducts)): ?>
        <h2>Лидеры продаж</h2>
        <div class="row">
            <?php foreach ($hitProducts as $item): ?>
            <div class="col-sm-4">
                <?= ProductWidget::widget(['product'=>$item]); ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($newProducts)): ?>
        <h2>Новинки</h2>
        <div class="row">
            <?php foreach ($newProducts as $item): ?>
                <div class="col-sm-4">
                    <?= ProductWidget::widget(['product'=>$item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($saleProducts)): ?>
        <h2>Распродажа</h2>
        <div class="row">
            <?php foreach ($saleProducts as $item): ?>
                <div class="col-sm-4">
                    <?= ProductWidget::widget(['product'=>$item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

