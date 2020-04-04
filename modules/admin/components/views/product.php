<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::to(['product/index']),'id'=>'saveform','options' => ['enctype' => 'multipart/form-data']]);
echo $form->field($product, 'name',['options'=>['class'=>'w-50']])->textInput(['value' => $product->name]);
echo $form->field($product, 'slug',['options'=>['class'=>'w-50']])->textInput(['value' => $product->slug]);
echo $form->field($product, 'price',['options'=>['class'=>'w-50']])->textInput(['value' => $product->price]);
echo $form->field($product, 'content',['options'=>['class'=>'w-50']])->textarea(['value' => $product->content]);
echo $form->field($product, 'keywords',['options'=>['class'=>'w-50']])->textInput(['value' => $product->keywords]);
echo $form->field($product, 'description',['options'=>['class'=>'w-50']])->textInput(['value' => $product->description]);
$tree = ArrayHelper::map($tree,'id','name');
echo $form->field($product,'category_id',['options'=>['class'=>'w-50']])->dropDownList($tree,['value'=>$product->category_id]);
if($product->id){
    echo $form->field($product, 'id')->hiddenInput(['value' => $product->id])->label(false);
}
echo $form->field($product, 'imageFile[]')->fileInput(['multiple' => true,'accept' => 'image/*'])->label('Фото');
$images = $product->images();
foreach ($images as $img){
    ?>
    <div class="pb-3">
        <img src="/images/product/<?=$img['image']?>" style="width:200px;height:200px;">
        <a href="?del_image=<?=$img['id']?>" onclick="return confirm('Действительно удалить?')">Удалить</a>
    </div>
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