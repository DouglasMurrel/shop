<?php
/*
 * Страница корзины покупателя, файл views/basket/index.php
 */

use app\components\SearchWidget;
use app\components\TreeWidget;
use app\components\BrandWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?= SearchWidget::widget(); ?>
<div class="col-sm-3">
    <h2>Каталог</h2>
    <div class="category-products">
        <?= TreeWidget::widget(); ?>
    </div>

    <h2>Бренды</h2>
    <div class="brand-products">
        <?= BrandWidget::widget(); ?>
    </div>
</div>

<div class="col-sm-9">
    <h1>Корзина</h1>
        <p class="text-right">
            <a href="<?= Url::to(['basket/clear']); ?>" class="text-danger">
                Очистить корзину
            </a>
        </p>
    <?
    $form = ActiveForm::begin(['options'=>['id'=>'mainform']]);
    ?>
        <table class="table table-bordered">
            <tr>
                <th>Наименование</th>
                <th>Количество</th>
                <th>Цена, руб.</th>
                <th>Сумма, руб.</th>
                <th></th>
            </tr>
            <?php if (!empty($basket) && !empty($basket['products'])): ?>
            <?php
                $k = 0;
                foreach ($basket['products'] as $id=>$item):
            ?>
                <tr>
                    <td><?= $item['name']; ?></td>
                    <td class="text-right">
                        <?= $form->field($basketForms[$k], "[$k]count")->textInput(['value'=>$item['count']])->label(false);?>
                        <?= $form->field($basketForms[$k], "[$k]id")->hiddenInput(['value'=>$id])->label(false); ?>
                    </td>
                    <td class="text-right"><?= $item['price']; ?></td>
                    <td class="text-right"><?= $item['price'] * $item['count']; ?></td>
                    <td class="text-right"><a href="<?= Url::to(['basket/remove','slug'=>$item['slug']]); ?>">Удалить</a></td>
                </tr>
            <?php
                    $k++;
                endforeach;
            ?>
            <?php endif; ?>
            <tr>
                <td colspan="3" class="text-right">Итого</td>
                <td class="text-right"><?= $basket['price']; ?></td>
                <td class="text-right"><a href='' onclick="$('form#mainform').submit();return false;">Пересчитать</a></td>
            </tr>
        </table>
    <a href="/checkout" class="float-right">Оформить заказ</a>
    <?
    ActiveForm::end();
    ?>
</div>