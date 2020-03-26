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
        <?= $form->field($order, 'name')->textInput(); ?>
        <?= $form->field($order, 'email')->input('email',['value'=>$email]); ?>
        <?= $form->field($order, 'phone')->textInput(); ?>
        <?= $form->field($order, 'address')->textarea(['rows' => 2]); ?>
        <?= $form->field($order, 'comment')->textarea(['rows' => 2]); ?>
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>