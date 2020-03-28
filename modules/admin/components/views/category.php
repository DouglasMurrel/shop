<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::to(['category/save']),'id'=>'saveform']);
echo $form->field($category, 'name',['options'=>['class'=>'w-25']])->textInput(['value' => $category->name]);
echo $form->field($category, 'slug',['options'=>['class'=>'w-25']])->textInput(['value' => $category->slug]);
echo $form->field($category, 'keywords',['options'=>['class'=>'w-25']])->textInput(['value' => $category->keywords]);
echo $form->field($category, 'description',['options'=>['class'=>'w-25']])->textInput(['value' => $category->description]);
array_unshift($tree,['id'=>1,'name'=>'Корень']);
$tree = ArrayHelper::map($tree,'id','name');
echo $form->field($category,'parent_id',['options'=>['class'=>'w-25']])->dropDownList($tree,['value'=>$parent_id]);
if($category->id){
    echo $form->field($category, 'id')->hiddenInput(['value' => $category->id]);
}
?>
<div class="pt-3">
    <?
echo Html::submitButton($category->id?"Сохранить":"Добавить", ['class' => 'btn btn-primary']);
    ?>
</div>
    <?
ActiveForm::end();