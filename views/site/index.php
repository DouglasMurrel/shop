<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';
?>
<div class="col-sm-9">
    <?php if (!empty($hitProducts)): ?>
        <h2>Лидеры продаж</h2>
        <div class="row">
            <?php foreach ($hitProducts as $item): ?>
                <?=show_product($item);?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($newProducts)): ?>
        <h2>Новинки</h2>
        <div class="row">
            <?php foreach ($newProducts as $item): ?>
                <?=show_product($item);?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($saleProducts)): ?>
        <h2>Распродажа</h2>
        <div class="row">
            <?php foreach ($saleProducts as $item): ?>
            <?=show_product($item);?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
function show_product($item){
    ob_start();
?>
    <div class="col-sm-4">
        <div class="product-wrapper text-center">
            <?=
            Html::img(
                '@web/images/products/medium/'.$item['image'],
                ['alt' => $item['name'], 'class' => 'img-responsive']
            );
            ?>
            <h2><?= $item['price']; ?> руб.</h2>
            <p>
                <a href="<?= Url::to(['catalog/product', 'slug' => $item['slug']]); ?>">
                    <?= Html::encode($item['name']); ?>
                </a>
            </p>
            <a href="#" class="btn btn-warning">
                <i class="fa fa-shopping-cart"></i>
                Добавить в корзину
            </a>
        </div>
    </div>
<?php
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
?>
