<?php
/*
 * Страница оформления заказа, файл views/order/checkout.php
 */

use app\components\SearchWidget;
use app\components\TreeWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'] = $links;

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>

<div class="col-sm-9">
    <h1>Оформление заказа</h1>
    <div id="checkout">
        <?php
        $form = ActiveForm::begin(
            ['id' => 'checkout-form', 'class' => 'form-horizontal']
        );
        ?>
        <?= $form->field($order, 'name')->textInput(['value'=>$name]); ?>
        <?if(Yii::$app->user->isGuest){?>
            <?= $form->field($order, 'email',['options'=>['class'=>'form-group field-order-email mb-0']])->input('email',['value'=>$email]); ?>
        <div class="mb-1">Если вы укажете email, то будете зарегистрированы на сайте и получите доступ к личному кабинету</div>
        <?}else{?>
            <?= $form->field($order, 'email',['options'=>['class'=>'form-group field-order-email']])->input('email',['value'=>$email]); ?>
        <?}?>
        <?= $form->field($order, 'phone')->textInput(['value'=>$phone]); ?>
        <?= $form->field($order, 'address')->textarea(['rows' => 2, 'value'=>$address]); ?>
        <?= $form->field($order, 'comment')->textarea(['rows' => 2]); ?>
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>