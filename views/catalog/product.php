<?php
/*
 * Страница товара, файл views/catalog/product.php
 */

use app\components\TreeWidget;
use app\components\SearchWidget;
use yii\bootstrap4\Carousel;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'] = $links;

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
$carousel = [];
foreach($images as $image) {
    $img = '/images/product/' . $image['image'];
    $file = Yii::getAlias('@webroot') . $img;
    $img_flag = 0;
    if (file_exists($file) && $image != ''){
        $carousel[] = $img;
        $img_flag = 1;
    }
}
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
                    <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="3000" style="width:200px;">
                        <div class="carousel-inner">
                            <?foreach ($carousel as $i=>$img){?>
                            <div class="carousel-item<?if($i==0){?> active<?}?>">
                                <img src="<?=$img?>" class="d-block" style="width:200px;height:200px;">
                            </div>
                            <?}?>
                        </div>
                        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Назад</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Вперед</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="product-descr">
                        <div><?=$product['content']?></div>
                    </div>
                    <div class="product-info">
                        <p class="product-price">
                            Цена: <span><?= $product['price']; ?></span> руб.
                        </p>
                        <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
                        <?
                        ob_start();
                        ?>
                        <a href="" onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;"><img src="/images/basket.png" alt="Добавить в корзину" title="Добавить в корзину"></a>
                        <?
                        $basketButton = ob_get_contents();
                        ob_end_clean();
                        ?>
                        <?= $form->field($basketForm, 'count',[
                            'options'=>['class'=>'w-100 d-inline-block'],
                            'template'=>"{label}{input}$basketButton{hint}{error}",
                        ])->textInput(['value'=>1,'style'=>'display:inline;width:90%;'])->label(false) ?>
                        <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
                <?
                }else{
                ?>
                    <div class="col">
                        <div class="product-descr">
                            <div><?=$product['content']?></div>
                        </div>
                        <div class="product-info">
                            <p class="product-price">
                                Цена: <span><?= $product['price']; ?></span> руб.
                            </p>
                            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
                            <?
                            ob_start();
                            ?>
                            <a href="" onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;"><img src="/images/basket.png" alt="Добавить в корзину" title="Добавить в корзину"></a>
                            <?
                            $basketButton = ob_get_contents();
                            ob_end_clean();
                            ?>
                            <?= $form->field($basketForm, 'count',[
                                    'options'=>['class'=>'w-lg-25 d-inline-block'],
                                    'template'=>"{label}{input}$basketButton{hint}{error}",
                            ])->textInput(['value'=>1,'style'=>'display:inline;width:80%;'])->label(false) ?>
                            <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                            <?php ActiveForm::end(); ?>

                        </div>
                    </div>
                <?
                }
                ?>
            </div>
        </div>
    </div>
</div>