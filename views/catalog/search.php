<?php
/*
 * Страница результатов поиска по каталогу, файл views/catalog/search.php
 */

use app\components\ProductWidget;
use app\components\SearchWidget;
use app\components\TreeWidget;
use app\components\BrandWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->params['breadcrumbs'] = [['label'=>'']];
?>

<section>
    <div class="container">
        <div class="row">
            <?= SearchWidget::widget(); ?>
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Каталог</h2>
                    <div class="category-products">
                        <?= TreeWidget::widget(); ?>
                    </div>

                    <h2>Бренды</h2>
                    <div class="brand-products">
                        <?= BrandWidget::widget(); ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <?php if (!empty($products)): ?>
                    <h2>Результаты поиска по каталогу</h2>
                    <div class="row">
                        <div class="container">
                            <?
                            foreach ($products as $product) {
                                ?>
                                <div class="col-sm-4">
                                    <?= ProductWidget::widget(['product'=>$product]); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?= LinkPager::widget(['pagination' => $pages]); /* постраничная навигация */ ?>
                <?php else: ?>
                    <p>По вашему запросу ничего не найдено.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
