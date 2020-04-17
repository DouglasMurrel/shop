<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::to(['color/update']),'id'=>'saveform']);

?>
<div class="pt-5">
    <?
    $i = 0;
    foreach ($allColors as $color){
        ?>
        <div class="color-field">
            <?
            echo $form->field($color, '['.$i.']name',['template'=>"<div class='row'>
    <div class='col-5'>
    {input}
    {error}
    </div>
    <div class='col-1'>
    <a href='".Url::to(['color/delete','id'=>$color->id])."' onclick='return confirm(\"Действительно удалить?\")'>Удалить</a>
    </div>
    </div>
    "])->textInput(['class'=>'form-control color-input','id'=>'color-name0']);
            echo $form->field($color,'['.$i.']id')->hiddenInput(['class'=>'form-control color-hidden'])->label(false);
            ?>
        </div>
        <?
        $i = $color->id+1;
    }
    ?>
</div>

<div id="new-colors" class="pt-5">
    <div class="color-field">
    <?
    echo $form->field($color, '['.$i.']name',['template'=>"<div class='row'>
    <div class='col-5'>
    {input}
    {error}
    </div>
    <div class='col-1'>
    <i class='fa fa-plus' onclick='return setColor(this)' style='cursor: pointer' aria-hidden='true'></i>
    </div>
    </div>
    "])->textInput(['class'=>'form-control color-input','id'=>'color-name0','value'=>'']);
    echo $form->field($color,'['.$i.']id')->hiddenInput(['class'=>'form-control color-hidden','value'=>$i])->label(false);
    ?>
    </div>
</div>
    <div class="pt-3">
        <?
        echo Html::submitButton("Сохранить", ['class' => 'btn btn-primary']);
        ?>
    </div>
<?php
ActiveForm::end();
?>