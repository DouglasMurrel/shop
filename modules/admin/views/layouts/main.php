<?php

use app\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = false;
Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = false;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <header>
        <div class="container">
        <?php
        echo Menu::widget([
            'items' => [
                ['label' => 'Заказы', 'url' => ['order/index']],
                [
                    'label' => 'Каталог',
                    'items' => [
                        ['label' => 'Категории', 'url' => ['category/index']],
                        ['label' => 'Товары', 'url' => ['product/index']],
                    ],
                ],
//                ['label' => 'Пользователи', 'url' => ['user/index']],
            ],
            'activeCssClass'=>'active',
            'options' => [
                'id'=>'menu',
                'class' => 'menu',
                'data-id'=>'menu',
            ],
        ])
        ?>
        </div>
    </header>

    <div class="container">
        <?= $content; ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
<?php
