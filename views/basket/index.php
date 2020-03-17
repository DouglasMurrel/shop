<?php
/*
 * Страница корзины покупателя, файл views/basket/index.php
 */

use app\components\SearchWidget;
use app\components\TreeWidget;
use app\components\BrandWidget;
use yii\helpers\Html;
use yii\helpers\Url;
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
    <?php if (!empty($basket) && !empty($basket['products'])): ?>
        <p class="text-right">
            <a href="<?= Url::to(['basket/clear']); ?>" class="text-danger">
                Очистить корзину
            </a>
        </p>
        <table class="table table-bordered">
            <tr>
                <th>Наименование</th>
                <th>Количество</th>
                <th>Цена, руб.</th>
                <th>Сумма, руб.</th>
            </tr>
            <?php foreach ($basket['products'] as $item): ?>
                <tr>
                    <td><?= $item['name']; ?></td>
                    <td class="text-right"><?= $item['count']; ?></td>
                    <td class="text-right"><?= $item['price']; ?></td>
                    <td class="text-right"><?= $item['price'] * $item['count']; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-right">Итого</td>
                <td class="text-right"><?= $basket['price']; ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Ваша корзина пуста</p>
    <?php endif; ?>
</div>