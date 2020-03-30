<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

if (!empty($orderItems)) {
    $form = ActiveForm::begin(['action' => Url::to(['orders/order','id'=>$order['id']])]);
    ?>
    <div><?=$order['created']?></div>
    <div><?=$order['name']?></div>
    <div><?=$order['phone']?></div>
    <div><?=$order['address']?></div>
    <div><?=$order['comment']?></div>
    <?= $form->field($order, "status")->dropDownList(Yii::$app->params['statuses'],['value'=>$order['status'],'style'=>['width'=>'200px;']])->label(false) ?>
    <table>
        <?
        foreach ($orderItems as $k=>$orderItem) {
            ?>
            <tr class="w-100 order_row">
                <td class="w-25">
                    <a href="<?= Url::to(['product/product', 'id' => $orderItem->product_id]); ?>">
                        <?= Html::encode($orderItem->name); ?>
                    </a>
                </td>
                <td class="w-25 price"><?= $orderItem->price; ?></td>
                <td class="w-25">
                    <?= $form->field($orderItem, "[$k]id",['options'=>['class'=>'m-0']])->hiddenInput(['value'=>$orderItem->id])->label(false) ?>
                    <?= $form->field($orderItem, "[$k]quantity",['options'=>['class'=>'w-auto d-inline-block']])
                        ->textInput(['value'=>$orderItem->quantity,'style'=>'width:100px;'])->label(false) ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <div><span id="full_amount"><?=$order['amount']?></span>  <a href="" onclick="recountOrder();return false;">Пересчитать</a></div>
    <?= $form->field($order, "id",['options'=>['class'=>'m-0']])->hiddenInput(['value'=>$order['id']])->label(false) ?>
    <?= Html::submitButton('Сохранить',['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>
    <?php
} else {
    echo '<p>Нет заказов</p>';
}
?>
