<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::to(['product/index']),'id'=>'saveform','options' => ['enctype' => 'multipart/form-data']]);
echo $form->field($product, 'name',['options'=>['class'=>'w-50']])->textInput(['value' => $product->name]);
echo $form->field($product, 'slug',['options'=>['class'=>'w-50']])->textInput(['value' => $product->slug]);
echo $form->field($product, 'price',['options'=>['class'=>'w-50']])->textInput(['value' => $product->price]);
echo $form->field($product, 'code',['options'=>['class'=>'w-50']])->textInput(['value' => $product->code]);
echo $form->field($product, 'corpus',['options'=>['class'=>'w-50']])->textInput(['value' => $product->corpus]);
echo $form->field($product, 'parameters',['options'=>['class'=>'w-50']])->textInput(['value' => $product->parameters]);
echo $form->field($product, 'keywords',['options'=>['class'=>'w-50']])->textInput(['value' => $product->keywords]);
echo $form->field($product, 'description',['options'=>['class'=>'w-50']])->textInput(['value' => $product->description]);
$tree = ArrayHelper::map($tree,'id','name');
echo $form->field($product,'category_id',['options'=>['class'=>'w-50']])->dropDownList($tree,['value'=>$product->category_id]);
if($product->id){
    echo $form->field($product, 'id')->hiddenInput(['value' => $product->id])->label(false);
}
echo $form->field($product, 'imageFile')->fileInput()->label('Фото');
$img = $product->firstImage();
if($img){
    ?>
    <img src="/images/product/<?=$img?>" style="width:200px;height:200px;">
    <?php
}
?>
    <div class="pt-3">
        <?
        echo Html::submitButton($product->id?"Сохранить":"Добавить", ['class' => 'btn btn-primary']);
        ?>
    </div>
<?
ActiveForm::end();