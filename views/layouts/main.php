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

<div class="container wrap">
    <div class="container fixed-top bg-light border-bottom border-dark">
        <div class="row">
            <div class="col-2 dont-show-lg" id="top">
                <button class="btn btn-white mt-1 dont-show-lg has_tooltip collapsed" type="button" data-toggle="collapse" data-target="#left-panel" aria-expanded="false" aria-controls="left-panel" title="Меню" data-placement="right">
                    <div class="gamb-line"></div>
                    <div class="gamb-line"></div>
                    <div class="gamb-line"></div>
                </button>
            </div>
            <div class="col-5">
                <a class="navbar-brand" href="<?=Yii::$app->homeUrl ?>"><?=Yii::$app->name ?></a>
            </div>
            <div class="col-5 clearfix">
                <?php
                $basketTitle = isset(Yii::$app->session['basketTitle']) ? Yii::$app->session['basketTitle'] : 'Корзина пуста';
                ?>
                <a class="nav-link float-right" href="<?=Url::to(['basket/index'])?>" title="<?=$basketTitle?>">Корзина</a>
            </div>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-6 col-lg-2 bg-light dont-collapse-lg collapse" id="left-panel" style="">
            <div class="d-flex flex-column pt-3">
                <?
                print Yii::$app->user->isGuest ? (
                    '<a class="nav-link my-auto text-primary" href="' . Url::to(["site/login"]) . '">Вход</a>'
                ) : (
                    Html::beginForm(['/site/logout'], 'post', ['class' => 'mt-n1 my-auto'])
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->email . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                )
                ?>
                <li><a class="nav-link my-auto text-primary" href="<?=Url::to(["site/about"])?>">О нас</a></li>
                <li><a class="nav-link my-auto text-primary" href="<?=Url::to(["site/contact"])?>">Обратная связь</a></li>
            </div>
        </div>
        <div class="col-12 col-lg-10" id="main-panel">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'navOptions' => ['style'=>'padding-top:10px;'],
            ]) ?>
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <? foreach (Yii::$app->session->getFlash('success') as $flash){?>
                    <div class="alert alert-success alert-dismissable" style="padding-top:40px;">
                        <div class="container">
                            <button aria-hidden="true" data-dismiss="alert" class="close pr-3" type="button">×</button>
                            <?= $flash ?>
                        </div>
                    </div>
                <? } ?>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <? foreach (Yii::$app->session->getFlash('error') as $flash){?>
                    <div class="alert alert-danger alert-dismissable" style="padding-top:40px;">
                        <div class="container">
                            <button aria-hidden="true" data-dismiss="alert" class="close pr-3" type="button">×</button>
                            <?= $flash ?>
                        </div>
                    </div>
                <? } ?>
            <?php endif; ?>
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
</div>

<footer class="footer">
    <div class="container">
        <p class="float-left">&copy; My Company <?= date('Y') ?></p>

        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


