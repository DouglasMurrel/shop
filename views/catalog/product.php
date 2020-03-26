<?php
/*
 * Страница товара, файл views/catalog/product.php
 */

use app\components\TreeWidget;
use app\components\SearchWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'] = $links;

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
$img = '/images/product/' . $image;
$file = Yii::getAlias('@webroot') . $img;
$img_flag = 0;
if (file_exists($file) && $image != '') $img_flag = 1;
?>
<?= SearchWidget::widget(); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1><?= Html::encode($product['name']); ?></h1>
            <div class="row mb-2">
                <?
                if ($img_flag == 1) {
                ?>
                <div class="col-sm-5">
                    <div class="product-image">
                            <div style="background-image:url('<?= $img ?>');background-size:contain;background-repeat: no-repeat;height:200px;"></div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="product-info">
                        <p class="product-price">
                            Цена: <span><?= $product['price']; ?></span> руб.
                        </p>
                        <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
                        <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-100 d-inline-block']])->textInput(['value'=>1])->label(false) ?>
                        <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                        <a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;">Добавить в корзину</a>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
                <?
                }else{
                ?>
                    <div class="col">
                        <div class="product-info">
                            <p class="product-price">
                                Цена: <span><?= $product['price']; ?></span> руб.
                            </p>
                            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
                            <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-lg-25 d-inline-block']])->textInput(['value'=>1])->label(false) ?>
                            <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                            <a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;">Добавить в корзину</a>
                            <?php ActiveForm::end(); ?>

                        </div>
                    </div>
                <?
                }
                ?>
            </div>
            <div class="product-descr">
                <?= $product['content']; ?>
            </div>
        </div>
    </div>
</div>
