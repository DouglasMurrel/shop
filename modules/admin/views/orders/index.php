<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\LinkPager;
use yii\helpers\Url;

if (!empty($orders)) {
    ?>
    <div>
        <?
        foreach ($orders as $order) {
            ?>
            <p>
                <a href="<?= Url::to(['orders/order', 'id' => $order['id']]); ?>">
                    <?= Html::encode($order['created']); ?>
                </a>
                <?= $order['name']; ?>
                <?= $order['email']; ?>
                <?= $order['phone']; ?>
            </p>
            <?php
        }
        ?>
    </div>
    <div class="ml-n3">
        <?= LinkPager::widget(['pagination' => $pages,'lastPageLabel'=>true,'firstPageLabel'=>true,'maxButtonCount'=>4]); /* постраничная навигация */ ?>
    </div>
    <?php
} else {
    echo '<p>Список заказов пуст</p>';
}
?>