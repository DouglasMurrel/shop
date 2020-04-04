<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

?>
<div class="row mb-2">
    <?
    foreach ($products as $product) {
        ?>
        <div class="product-wrapper text-center p-1 m-1 border border-primary">
            <?
            $img = '/images/product/'.$product['image'];
            $file = Yii::getAlias('@webroot').$img;
            if(!file_exists($file) || $product['image']=='')$img = '/images/noimage.jpg';
            ?>
            <div style="background-image:url('<?=$img?>');background-size:contain;background-repeat: no-repeat;background-position-x:center;height:100px;"></div>
            <h2><?= $product['price']; ?> руб.</h2>
            <p>
                <a href="<?= Url::to(['catalog/product', 'slug' => $product['slug']]); ?>">
                    <?= Html::encode($product['name']); ?>
                </a>
            </p>
            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
            <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
            <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-auto d-inline-block']])->textInput(['value'=>1])->label(false) ?>
            <p><a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;">Добавить в корзину</a></p>
            <?php ActiveForm::end(); ?>
        </div>
        <?php
    }

?>
</div>
<div class="ml-n3">
    <?= LinkPager::widget(['pagination' => $pages,'lastPageLabel'=>true,'firstPageLabel'=>true,'maxButtonCount'=>4]); /* постраничная навигация */ ?>
</div>
