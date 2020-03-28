<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

if($tree){
    ActiveForm::begin(['action' => Url::to(['category/delete']),'id'=>'delform']);
    foreach ($tree as $k=>$node){
        $depth = $node->depth;
        $depth1 = $depth-1;
        $name = $node->name;
        $id = $node->id;
?>
        <div>
<?php
        for($i=0;$i<$depth1;$i++){
?>
            <div style="width:15px;display:inline-block;"></div>
<?
        }
?>
            <?=
            Html::checkbox('del[]',false,['id'=>"del$k",'value'=>$k]);
            ?>
            <a href="<?=Url::to(['category/category','id'=>$id])?>"><?=$name?></a>
        </div>
<?php
    }
    echo Html::button('Удалить выбранное', ['class' => 'btn btn-danger']);
    ActiveForm::end();
}