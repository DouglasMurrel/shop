<?php
/*
 * Файл components/views/brands.php
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="product-wrapper text-center">
    <?=
    Html::img(
        '@web/images/product/'.$product['image'],
        ['alt' => $product['name'], 'class' => 'img-responsive']
    );
    ?>
    <h2><?= $product['price']; ?> руб.</h2>
    <p>
        <a href="<?= Url::to(['catalog/product', 'slug' => $product['slug']]); ?>">
            <?= Html::encode($product['name']); ?>
        </a>
    </p>
    <a href="#" class="btn btn-warning">
        <i class="fa fa-shopping-cart"></i>
        Добавить в корзину
    </a>
</div>
