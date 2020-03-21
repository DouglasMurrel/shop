<?php
/*
 * Страница товара, файл views/catalog/product.php
 */

use app\components\TreeWidget;
use app\components\BrandWidget;
use app\components\SearchWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'] = $links;

if(isset($name) && $name!='')$this->title = $name;
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>
<section>
    <div class="container">
        <div class="row">
            <?= SearchWidget::widget(); ?>
            <div class="col-sm-3">
                <h2>Каталог</h2>
                <div class="category-products">
                    <?= TreeWidget::widget(); ?>
                </div>

                <h2><a href="<?= Url::to(['catalog/brands']); ?>" class='text-nowrap'>Бренды</a></h2>
                <div class="brand-products">
                    <?= BrandWidget::widget(); ?>
                </div>
            </div>

            <div class="col-sm-9">
                <h1><?= Html::encode($product['name']); ?></h1>
                <div class="row">
                    <div class="col-sm-5">
                        <?
                        if(count($images)>0) {
                            foreach ($images as $image) {
                                ?>
                                <div class="product-image">
                                    <?
                                    $img = '/images/product/' . $image['image'];
                                    $file = Yii::getAlias('@webroot') . $img;
                                    if (!file_exists($file) || $image['image'] == '') $img = '/images/noimage.jpg';
                                    ?>
                                    <div style="background-image:url('<?=$img?>');background-size:contain;background-repeat: no-repeat;height:200px;"></div>
                                </div>
                            <?
                            }
                        }else{
                            ?>
                            <div style="background-image:url('/images/noimage.jpg');background-size:contain;background-repeat: no-repeat;height:200px;"></div>
                        <?
                        }?>
                    </div>
                    <div class="col-sm-7">
                        <div class="product-info">
                            <p class="product-price">
                                Цена: <span><?= $product['price']; ?></span> руб.
                            </p>
                            <p>
                                Бренд:
                                <a href="<?= Url::to(['catalog/brand', 'slug' => $brand['slug']]); ?>">
                                    <?= Html::encode($brand['name']); ?>
                                </a>
                            </p>
                            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
                            <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-25 d-inline-block']])->textInput(['value'=>1])->label(false) ?>
                            <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                            <a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;">Добавить в корзину</a>
                            <?php ActiveForm::end(); ?>

                        </div>
                    </div>
                </div>
                <div class="product-descr">
                    <?= $product['content']; ?>
                </div>
                <?php if (!empty($similar)): /* похожие товары */ ?>
                    <h2>Похожие товары</h2>
                    <div class="row">
                        <?php foreach ($similar as $item): ?>
                            <div class="col-sm-4">
                                <div class="product-wrapper text-center">
                                    <?
                                    $img = '/images/product/'.$item['image'];
                                    $file = Yii::getAlias('@webroot').$img;
                                    if(!file_exists($file) || $item['image']=='')$img = '/images/noimage.jpg';
                                    ?>
                                    <div style="background-image:url('<?=$img?>');background-size:contain;background-repeat: no-repeat;height:100px;"></div>
                                    <h2><?= $item['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'slug' => $item['slug']]); ?>">
                                            <?= Html::encode($item['name']); ?>
                                        </a>
                                    </p>
                                    <?php
                                    if ($product['new']) { // новинка?
                                        echo Html::tag(
                                            'span',
                                            'Новинка',
                                            ['class' => 'new']
                                        );
                                    }
                                    if ($product['hit']) { // лидер продаж?
                                        echo Html::tag(
                                            'span',
                                            'Лидер продаж',
                                            ['class' => 'hit']
                                        );
                                    }
                                    if ($product['sale']) { // распродажа?
                                        echo Html::tag(
                                            'span',
                                            'Распродажа',
                                            ['class' => 'sale']
                                        );
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
