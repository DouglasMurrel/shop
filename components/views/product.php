<?php
/*
 * Файл components/views/brands.php
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
    <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add'])]); ?>
    <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
    <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-25 d-inline-block']])->textInput(['value'=>1])->label(false) ?>
    <?= Html::submitButton('Добавить в корзину', ['class' => 'btn btn-warning']) ?>
    <?php ActiveForm::end(); ?>
</div>
