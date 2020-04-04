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
        <div class="row top-row">
            <?
            if(!Yii::$app->user->isGuest){
                ?>
            <div class="dont-show-mobile col-lg-6">
                <a class="navbar-brand" href="<?=Yii::$app->homeUrl ?>"><?=Yii::$app->name ?></a>
                <?
                if(Yii::$app->user->identity->isAdmin()){
                    ?>
                    <a class="navbar-brand" target='_blank' href="<?=Url::to('admin')?>">Admin</a>
                    <?
                }
                ?>
            </div>
            <div class="col-lg-1 col-3 p-0">
                <?php
                $basketTitle = isset(Yii::$app->session['basketTitle']) ? Yii::$app->session['basketTitle'] : 'Корзина пуста';
                ?>
                <a class="nav-link float-right" href="<?=Url::to(['basket/index'])?>" title="<?=$basketTitle?>">Корзина</a>
            </div>
                <div class="col-lg-2 col-4 p-0">
                    <a class="nav-link my-auto" href="<?=Url::to(["user/orders"])?>">Мои заказы</a>
                </div>
            <div class="col-3 p-0">
                <?=                Html::beginForm(['/site/logout'], 'post', ['class' => 'mt-n1 my-auto'])
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->email . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                ?>
            </div>
                <?
            }else{
                ?>
                <div class="col-8 col-lg-10">
                    <a class="navbar-brand" href="<?=Yii::$app->homeUrl ?>"><?=Yii::$app->name ?></a>
                </div>
                <div class="col-1">
                    <?php
                    $basketTitle = isset(Yii::$app->session['basketTitle']) ? Yii::$app->session['basketTitle'] : 'Корзина пуста';
                    ?>
                    <a class="nav-link float-right" href="<?=Url::to(['basket/index'])?>" title="<?=$basketTitle?>">Корзина</a>
                </div>
                <div class="col-1">
                    <a class="nav-link my-auto text-primary" href="<?= Url::to(["site/login"])?>">Вход</a>
                </div>
            <?
            }
            ?>
        </div>
    </div>
    <div class="row submenu-row bg-light">
        <div class="col-4 col-lg-3"><a class="nav-link my-auto" href="<?=Url::to(["site/about"])?>">О нас</a></div>
        <div class="col-4 col-lg-3"><a class="nav-link my-auto" href="<?=Url::to(["site/contact"])?>">Контакты</a></div>
        <div class="col-5 col-lg-3"><a class="nav-link my-auto" href="<?=Url::to(["site/howtobuy"])?>">Как купить</a></div>
        <div class="col col-lg-3"><a class="nav-link my-auto" href="<?=Url::to(["site/payment"])?>">Доставка и оплата</a></div>
    </div>
    <div class="row">
        <?= Alert::widget(['options'=>['class'=>'w-100']]) ?>
        <div class="col-12" id="main-panel">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'navOptions' => ['style'=>'padding-top:10px;'],
            ]) ?>
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

        <p class="float-right">Сделано в <a href="https://yourwebstudio.ru">Вашей веб-студии</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


