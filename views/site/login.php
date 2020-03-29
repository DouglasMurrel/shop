<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [['label'=>'']];

if($active=='login')$this->title = 'Вход';
if($active=='register')$this->title = 'Регистрация';
if($active=='recover')$this->title = 'Вспомнить пароль';
?>
<div class="site-index">

    <div class="body-content">
        <? if(Yii::$app->user->isGuest) {?>
            <div class="row justify-content-center mt-3 mb-3 ml-1">
                <ul class="nav nav-tabs col-lg-12" role="tablist">
                    <li class="nav-item"><a class="nav-link pl-1 pr-1 pl-lg-3 pr-lg-3<?if($active=='login'){?> active<?}?>" href="#login" aria-controls="login" role="tab" data-toggle="tab">Вход</a></li>
                    <li class="nav-item"><a class="nav-link pl-1 pr-1 pl-lg-3 pr-lg-3<?if($active=='register'){?> active<?}?>" href="#register" aria-controls="register" role="tab" data-toggle="tab">Регистрация</a></li>
                    <li class="nav-item"><a class="nav-link pl-1 pr-1 pl-lg-3 pr-lg-3<?if($active=='recover'){?> active<?}?>" href="#recover" aria-controls="recover" role="tab" data-toggle="tab">Вспомнить пароль</a></li>
                </ul>
            </div>
            <div class="row justify-content-center ml-1">
                <div class="col-lg-12 tab-content">
                    <div role="tabpanel" class="tab-pane<?if($active=='login'){?> active<?}?>" id="login">
                        <?php
                        $form = ActiveForm::begin(['id' => 'login-form','action'=>'login']);
                        ?>

                        <?= $form->field($modelLogin, 'email')->input('email')->label('EMail'); ?>
                        <?= $form->field($modelLogin, 'password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput()->label('Пароль') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane<?if($active=='register'){?> active<?}?>" id="register">
                        <?php
                        $form = ActiveForm::begin(['id' => 'register-form','action'=>'register','enableAjaxValidation' => true]);
                        ?>

                        <?= $form->field($modelRegister, 'email')->input('email')->label('EMail'); ?>
                        <?= $form->field($modelRegister, 'password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput()->label('Введите пароль') ?>
                        <?= $form->field($modelRegister, 'password2', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput()->label('Повторите пароль') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
                        </div>

                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane<?if($active=='recover'){?> active<?}?>" id="recover">
                        <?php
                        Pjax::begin([]);
                        $form = ActiveForm::begin(['id' => 'recover-form','action'=>'recover','options' => ['data' => ['pjax' => true]]]);
                        ?>

                        <?= $form->field($modelRecover, 'email', ['enableAjaxValidation' => true])->input('email')->label('EMail'); ?>
                        <div class="form-group">
                            <?= Html::submitButton('Сбросить пароль', ['class' => 'btn btn-primary', 'name' => 'recover-button']) ?>
                        </div>
                        <div id="progress" style="display: none">Минуту...</div>

                        <?php
                        ActiveForm::end();
                        Pjax::end();
                        ?>
                        <script>
                            $(function () {
                                $(document).on('pjax:send', function() {
                                    $(document).css('cursor','wait');
                                    $('#progress').show();
                                }).on('pjax:complete', function() {
                                    $(document).css('cursor','default');
                                    $('#progress').hide();
                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        <? } ?>
    </div>
</div>
