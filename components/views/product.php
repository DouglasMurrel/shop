<?php
/*
 * Файл components/views/brands.php
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<tr class="w-100">
    <td class="w-25">
        <a href="<?= Url::to(['catalog/product', 'slug' => $product['slug']]); ?>">
            <?= Html::encode($product['name']); ?>
        </a>
    </td>
    <td class="w-25"><?= $product['corpus']; ?></td>
    <td class="w-25"><?= $product['price']; ?> руб.</td>
    <td class="w-25">
    <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
    <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
    <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-auto d-inline-block']])->textInput(['value'=>1,'style'=>'width:70px;'])->label(false) ?>
    <a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;">Добавить в корзину</a>
    <?php ActiveForm::end(); ?>
    </td>
</tr>
