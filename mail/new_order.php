<?php

use yii\helpers\Html;

?>
<h1>Поступил новый заказ № <?=$order->id?></h1>

<ul>
    <li>Покупатель: <?= Html::encode($order->name); ?></li>
    <li>E-mail: <?= Html::encode($order->email); ?></li>
    <li>Телефон: <?= Html::encode($order->phone); ?></li>
</ul>

<table border="1" cellpadding="3" cellspacing="0">
    <tr>
        <th align="left">Наименование</th>
        <th align="left">Кол-во, шт</th>
        <th align="left">Цена, руб.</th>
        <th align="left">Сумма, руб.</th>
    </tr>
    <?php foreach ($order->orderItems as $product): ?>
        <tr>
            <td align="left"><?= Html::encode($product['name']); ?></td>
            <td align="right"><?= $product['quantity']; ?></td>
            <td align="right"><?= $product['price']; ?></td>
            <td align="right"><?= $product['cost']; ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3" align="right">Итого</td>
        <td align="right"><?= $order['amount']; ?></td>
    </tr>
    <tr>
        <td colspan="3" align="right">Со скидкой</td>
        <td align="right"><?= $order['discount']; ?></td>
    </tr>
</table>

<p>Адрес доставки: <?= Html::encode($order->address); ?></p>

<p>Комментарий: <?= Html::encode($order->comment); ?></p>
