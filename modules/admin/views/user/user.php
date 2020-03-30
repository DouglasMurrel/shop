<?php

use yii\helpers\Url;

?>
<h1>Заказы для <?=$user['email']?></h1>
<?php

foreach($orders as $order){
    ?>
    <div>
        <a href="<?=Url::to(['orders/order','id'=>$order['id']])?>"><?=$order['created']?></a>
        <div style="display:inline-block;padding-left:15px;"><?=isset(Yii::$app->params['statuses'][$order['status']])?$statuses[$order['status']]:''?></div>
    </div>
<?php
}
