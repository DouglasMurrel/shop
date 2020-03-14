<?php

use app\components\BrandWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'] = $links;

if($name!='')$this->title = $name;
if($description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if($keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>
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
<div class="col-sm-9 padding-right">
    <h1>Все бренды</h1>
<?php
    if (!empty($brands)):
?>
        <div class="row">
            <?php foreach ($brands as $brand): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <h2>
                                <a href="<?= Url::to(['catalog/brand', 'slug' => $brand['slug']]); ?>">
                                    <?= Html::encode($brand['name']); ?>
                                </a>
                            </h2>
                            <p><?= Html::encode($brand['content']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
