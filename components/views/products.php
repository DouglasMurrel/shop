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
            if(isset($product['image'])) {
                $img = '/images/product/' . $product['image'];
                $file = Yii::getAlias('@webroot') . $img;
                if (!file_exists($file) || $product['image'] == '') $img = '/images/noimage.jpg';
            }else $img = '/images/noimage.jpg';
            ?>
            <div style="background-image:url('<?=$img?>');background-size:contain;background-repeat: no-repeat;background-position-x:center;height:100px;"></div>
            <h2><?= $product['price']; ?> руб.</h2>
            <p>
                <a href="<?= Url::to(['catalog/product', 'slug' => $product['slug']]); ?>">
                    <?= Html::encode($product['name']); ?>
                </a>
            </p>
            <? if(!$indexFlag){?>
            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id'],'style'=>'min-width:226px;']]); ?>
                <p>
                    <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                    <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-auto d-inline-block']])->textInput(['value'=>1,'style'=>'max-width:100px;'])->label(false) ?>
                    <a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;padding-left:15px;"><img src="/images/basket.png" alt="Добавить в корзину" title="Добавить в корзину"></a>
                </p>
            <?php ActiveForm::end(); ?>
            <?}else{?>
                <div style="min-width:226px;"></div>
            <?}?>
        </div>
        <?php
    }

?>
</div>
<?if($pages){?>
<div class="ml-n3">
    <?= LinkPager::widget(['pagination' => $pages,'lastPageLabel'=>true,'firstPageLabel'=>true,'maxButtonCount'=>4]); /* постраничная навигация */ ?>
</div>
<?}?>
