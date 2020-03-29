<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\LinkPager;
use yii\helpers\Url;
use app\modules\admin\components\ProductWidget;

?>
<div class="row">
    <?php
    $pages = $products['pages'];
    $products = $products['products'];
    ?>
    <div class="col">
        <?
        if($products){
            ActiveForm::begin(['action' => Url::to(['product/delete']), 'id' => 'delform']);
            foreach ($products as $k => $cur_product) {
                $name = $cur_product->name;
                $id = $cur_product->id;
                $category = $cur_product->category;
                ?>
                <div>
                    <?=
                    Html::checkbox('del[]', false, ['id' => "del$k", 'value' => $id]);
                    ?>
                    <a href="<?= Url::to(['product/product', 'id' => $id]) ?>"><?= $name ?></a>
                    <?= $category->name ?>
                </div>
                <?php
            }
            echo Html::button('Удалить выбранное', ['class' => 'btn btn-danger']);
            ActiveForm::end();
            ?>
            <div class="ml-n3 mt-3">
                <?= LinkPager::widget(['pagination' => $pages, 'lastPageLabel' => true, 'firstPageLabel' => true, 'maxButtonCount' => 4]); /* постраничная навигация */ ?>
            </div>
            <hr>
            <?
        }
        ?>
        <div>
            <?ActiveForm::begin(['action' => Url::to(['product/loader']),'options' => ['enctype' => 'multipart/form-data']]);?>
            <div><input type="file" name="xlsFile" accept=".xls,.xlsx"></div>
            <div class="pt-2"><input type="submit" class='btn btn-primary' value="Загрузить из Excel"></div>
            <?ActiveForm::end()?>
        </div>
    </div>
    <div class="col">
        <h2>Добавить товар</h2>
        <?php
        echo ProductWidget::widget(['product'=>$product,'tree'=>$tree]);
        ?>
    </div>
</div>