<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="col-sm-12">
    <?$form = ActiveForm::begin(['id' => 'search-form', 'options'=>['class'=>'pull-left'], 'method'=>'get','action'=>Url::to(['catalog/search'])]);?>
    <div class="input-group">
        <?= $form->field($modelSearch, 'query')->textInput(['placeholder'=>'Поиск по каталогу','name'=>'query','value'=>$query])->label(false) ?>
        <div class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="w-100"></div>