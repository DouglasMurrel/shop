<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [['label'=>'','template'=>'']];

$this->title = 'Сброс пароля';
?>
<? if(Yii::$app->user->isGuest) {?>
    <div class="col-lg-8 tab-content">
        <?php
        Pjax::begin([]);
        $form = ActiveForm::begin(['id' => 'reset-form','action'=>Url::to(),'options' => ['data' => ['pjax' => true]]]);
        ?>

        <div>Ваш адрес: <?= $email ?></div>
        <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput()->label('Введите новый пароль') ?>
        <?= $form->field($model, 'password2', ['inputOptions' => ['autocomplete' => 'new-password'], 'enableAjaxValidation' => true])->passwordInput()->label('Повторите новый пароль') ?>

        <div class="form-group">
            <?= Html::submitButton('Сменить пароль', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>

        <?php
        ActiveForm::end();
        Pjax::end();
        ?>
    </div>
<? } ?>
