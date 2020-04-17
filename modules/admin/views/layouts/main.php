<?php

use app\assets\AppAsset;
use app\modules\admin\AdminAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = false;
Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = false;
AppAsset::register($this);
AdminAsset::register($this);
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
        <script src="https://cdn.tiny.cloud/1/mm8sxuca1unhwjbzucera77k30tsoylpbofhe614slgx7unz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    </head>
    <body style="background-color: white !important;">
    <?php $this->beginBody() ?>
    <header>
        <div class="container">
        <?php
        echo Menu::widget([
            'items' => [
                ['label' => 'Заказы', 'url' => ['orders/index']],
                [
                    'label' => 'Каталог',
                    'items' => [
                        ['label' => 'Категории', 'url' => ['category/index']],
                        ['label' => 'Товары', 'url' => ['product/index']],
                    ],
                ],
                ['label' => 'Пользователи', 'url' => ['user/index']],
                ['label' => 'Цвета', 'url' => ['color/index']],
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
