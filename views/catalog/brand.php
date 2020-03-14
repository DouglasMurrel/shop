<?php

use app\components\BrandWidget;
use app\components\ProductWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

if($name!='')$this->title = $name;
if($description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if($keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>
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
    <h1><?=$name?></h1>
    <p><?=$content?></p>
    <p>    <?=
        Html::img(
            '@web/images/brand/'.$image,
            ['alt' => $name, 'class' => 'img-responsive']
        );
        ?></p>
    <?php
    if (!empty($products)) {
        ?>
        <div class="container">
            <?
            foreach ($products as $product) {
                ?>
                <div class="col-sm-4">
                    <?= ProductWidget::widget(['product'=>$product]); ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div>
            <?= LinkPager::widget(['pagination' => $pages]); /* постраничная навигация */ ?>
        </div>
        <?php
    } else {
        echo '<p>Нет товаров этого бренда</p>';
    }
    ?>
</div>
