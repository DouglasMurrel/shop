<?php
use yii\helpers\Html;
$this->title = 'Заказ в магазине № ' . $order->id;
?>

<h1><?= Html::encode($this->title); ?></h1>

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
        <td align="right"><?= $order['discount']; ?><br>(самый дорогой товар - без скидки, второй - скидка 10%, остальные - 20%)</td>
    </tr>
</table>

<p>Адрес доставки: <?= Html::encode($order->address); ?></p>

<p>Комментарий: <?= Html::encode($order->comment); ?></p>

<p>
    Статус вашего заказа вы можете посмотреть в своем личном кабинете на <a href="<?=$site?>">нашем сайте</a>.
</p>
<?php
if($password){
?>
    Войти на сайт вы можете на странице <a href="<?=$site?>/login"><?=$site?>/login</a>, используя ваш адрес электронной почты. Ваш пароль - <?=$password?>. Сохраните его для дальнейшего использования.
<?
}
?>