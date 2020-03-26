<?
foreach ($orders as $order){
    ?>
    <div class="row order-item border border-primary w-100 mb-2">
        <div class="col">
            <?
            $order['status'];
            if($order['status']==0)$status='Ожидает';
            if($order['status']==1)$status='Выполняется';
            if($order['status']==2)$status='Выполнен';
            ?>
            Заказ <?=$order['id']?>, <?=$order['created']?>, статус: <?=$status?>
        </div>
        <div class="w-100"></div>
        <div class="col">
            <?=$order['name']?>,
        </div>
        <div class="col">
            <?=$order['phone']?>
        </div>
        <div class="col">
            <?=$order['email']?>
        </div>
        <div class="w-100"></div>
        <div class="col-12">
            <?=$order['address']?>
        </div>
        <div class="w-100"></div>
        <div class="col-12">
            <?=$order['comment']?>
        </div>
        <div class="w-100"></div>
        <div class="col-12">
            <?
            $arr_products = [];
            $sum = 0;
            $products = $order['items'];
            foreach($products as $product){
                $str_product = $product['name'].' '.$product['quantity'].' шт., стоимость '.$product['cost'].' руб.';
                $arr_products[] = "<div>$str_product</div>";
                $sum += $product['cost'];
            }
            print implode('',$arr_products);
            print "Итого: $sum руб.";
            ?>
        </div>
    </div>
    <?php
}
?>