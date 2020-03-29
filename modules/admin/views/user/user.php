<?php

use yii\helpers\Url;

?>
<h1>Заказы для <?=$user['email']?></h1>
<?php

$statuses = [0=>'Ожидает',1=>'В обработке',2=>'Выполнен'];
foreach($orders as $order){
    ?>
    <div>
        <a href="<?=Url::to(['orders/order','id'=>$order['id']])?>"><?=$order['created']?></a>
        <div style="display:inline-block;padding-left:15px;"><?=isset($statuses[$order['status']])?$statuses[$order['status']]:''?></div>
    </div>
<?php
}
