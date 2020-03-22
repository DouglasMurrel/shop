<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use \app\components\MyNavBar;

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

<div class="wrap">
    <?php
    $basketTitle = isset(Yii::$app->session['basketTitle']) ? Yii::$app->session['basketTitle'] : 'Корзина пуста';
    $beforeCollapse = '<a class="nav-link" href="'.Url::to(['basket/index']).'" title="'.$basketTitle.'">Корзина</a>';
    MyNavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'fixed-top navbar-light bg-light border-bottom border-dark navbar-expand-lg',
        ],
        'collapseOptions' => [
            'class' => 'collapse navbar-collapse justify-content-end',
        ],
        'beforeCollapse' => $beforeCollapse,
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            '<a class="nav-link my-auto text-primary" href="'.Url::to(["site/about"]).'">О нас</a>',
            '<a class="nav-link my-auto text-primary" href="'.Url::to(["site/contact"]).'">Обратная связь</a>',
            Yii::$app->user->isGuest ? (
            '<a class="nav-link my-auto text-primary" href="'.Url::to(["site/login"]).'">Вход</a>'
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post',['class'=>'mt-n1 my-auto'])
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->email . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    MyNavBar::end();
    ?>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <? foreach (Yii::$app->session->getFlash('success') as $flash){?>}
        <div class="alert alert-success alert-dismissable" style="padding-top:40px;">
            <div class="container">
                <button aria-hidden="true" data-dismiss="alert" class="close pr-3" type="button">×</button>
                <?= $flash ?>
            </div>
        </div>
    <? } ?>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissable" style="padding-top:60px;">
            <div class="container">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'navOptions' => ['style'=>'padding-top:10px;'],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="site-index">
            <div class="container">
                <div class="row">
                    <?=$content?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


