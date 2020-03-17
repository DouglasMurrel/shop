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
                        <?foreach($images as $image){?>
                        <div class="product-image">
                            <?=
                            Html::img(
                                '@web/images/product/'.$image['image'],
                                ['alt' => $product['name'], 'class' => 'img-responsive']
                            );
                            ?>
                        </div>
                        <?}?>
                    </div>
                    <div class="col-sm-7">
                        <div class="product-info">
                            <p class="product-price">
                                Цена: <span><?= $product['price']; ?></span> руб.
                            </p>
                            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add'])]); ?>
                            <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-25 d-inline-block']])->textInput(['value'=>1])->label(false) ?>
                            <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                            <?= Html::submitButton('Добавить в корзину', ['class' => 'btn btn-warning']) ?>
                            <?php ActiveForm::end(); ?>
                            <p>
                                Бренд:
                                <a href="<?= Url::to(['catalog/brand', 'slug' => $brand['slug']]); ?>">
                                    <?= Html::encode($brand['name']); ?>
                                </a>
                            </p>

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
                                    <?=
                                    Html::img(
                                        '@web/images/product/'.$item['image'],
                                        ['alt' => $item['name'], 'class' => 'img-responsive']
                                    );
                                    ?>
                                    <h2><?= $item['price']; ?> руб.</h2>
                                    <p>
                                        <a href="<?= Url::to(['catalog/product', 'slug' => $item['slug']]); ?>">
                                            <?= Html::encode($item['name']); ?>
                                        </a>
                                    </p>
                                    <a href="#" class="btn btn-warning">
                                        <i class="fa fa-shopping-cart"></i>
                                        Добавить в корзину
                                    </a>
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
