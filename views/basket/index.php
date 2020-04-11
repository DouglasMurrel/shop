<?php
/*
 * Страница корзины покупателя, файл views/basket/index.php
 */

use app\components\SearchWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'] = [['label'=>'','template'=>'']];

$this->title = "Корзина";

?>

<div class="col">
    <h1>Корзина</h1>
        <p class="text-right">
            <a href="<?= Url::to(['basket/clear']); ?>" class="text-danger">
                Очистить корзину
            </a>
        </p>
    <?
    $form = ActiveForm::begin(['options'=>['id'=>'mainform']]);
    ?>
    <div class="container">
        <?php if (!empty($basket) && !empty($basket['products'])): ?>
            <?php
            $k = 0;
            foreach ($basket['products'] as $id=>$item):
                ?>
                <div class="row border border-primary">
                    <div class="col-lg"><?= $item['name']; ?></div>
                    <div class="col-lg text-lg-right">
                        <?= $form->field($basketForms[$k], "[$k]count")->textInput(['value'=>$item['count']])->label(false);?>
                        <?= $form->field($basketForms[$k], "[$k]id")->hiddenInput(['value'=>$id])->label(false); ?>
                    </div>
                    <div class="col-lg text-lg-right">
                        <?
                            $price = $item['price'];
                        ?>
                            Цена: <?= $price ?> р.
                    </div>
                    <div class="col-lg text-lg-right">Сумма: <?= $price * $item['count']; ?> р.</div>
                    <div class="col-lg text-lg-right"><a href="<?= Url::to(['basket/remove','slug'=>$item['slug']]); ?>">Удалить</a></div>
                </div>
                <?php
                $k++;
            endforeach;
            ?>
        <?php endif; ?>
        <tr>
            <div class="col-lg text-right">Итого: <?= $basket['price']; ?> руб.</div>
            <div class="col-lg text-right">Со скидкой: <?= $basket['discount_price']; ?> руб.</div>
            <div class="col-lg text-right" style="font-size:x-small">(самый дорогой товар - без скидки, второй - скидка 10%, остальные - 20%)</div>
            <div class="col-lg text-right"><a href='' onclick="$('form#mainform').submit();return false;">Пересчитать общую сумму</a></div>
        </tr>
    </div>
    <div style="padding-right:30px;">
        <a href="/checkout" class="float-right">Оформить заказ</a>
    </div>
    <?
    ActiveForm::end();
    ?>
</div>