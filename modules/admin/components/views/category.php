<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::to(['category/index']),'id'=>'saveform']);
echo $form->field($category, 'name')->textInput(['value' => $category->name]);
echo $form->field($category, 'slug')->textInput(['value' => $category->slug]);
echo $form->field($category, 'content')->textarea(['value' => $category->content, 'class'=>'form-control tinymce']);
echo $form->field($category, 'keywords')->textInput(['value' => $category->keywords]);
echo $form->field($category, 'description')->textInput(['value' => $category->description]);
array_unshift($tree,['id'=>1,'name'=>'Корень']);
$tree = ArrayHelper::map($tree,'id','name');
echo $form->field($category,'parent_id')->dropDownList($tree,['value'=>$parent_id]);
if($category->id){
    echo $form->field($category, 'id')->hiddenInput(['value' => $category->id])->label(false);
}
?>
<div class="pt-3">
    <?
echo Html::submitButton($category->id?"Сохранить":"Добавить", ['class' => 'btn btn-primary']);
    ?>
</div>
    <?
ActiveForm::end();