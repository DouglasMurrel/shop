<?php
/*
 * Страница оформления заказа, файл views/order/checkout.php
 */

use app\components\SearchWidget;
use app\components\TreeWidget;
use app\components\BrandWidget;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'] = $links;

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>

<?= SearchWidget::widget(); ?>
<div class="col-sm-3">
    <div class="left-sidebar">
        <h2>Каталог</h2>
        <div class="category-products container pl-0">
            <?= TreeWidget::widget(); ?>
        </div>

        <h2><a href="<?= Url::to(['catalog/brands']); ?>" class='text-nowrap'>Бренды</a></h2>
        <div class="brand-products">
            <?= BrandWidget::widget(); ?>
        </div>
    </div>
</div>

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