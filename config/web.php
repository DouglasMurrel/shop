<?php

$params = require __DIR__ . '/params.php';
if(file_exists(__DIR__ . '/params_local.php'))$params = require __DIR__ . '/params_local.php';
$db = require __DIR__ . '/db.php';
if(file_exists(__DIR__ . '/db_local.php'))$db = require __DIR__ . '/db_local.php';
$mail = require __DIR__ . '/mail.php';
if(file_exists(__DIR__ . '/mail_local.php'))$mail = require __DIR__ . '/mail_local.php';

$config = [
    'id' => 'basic',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'catalog/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'main',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6d4-RQbMeE8XlXbOv0My0u38QLtsXLVd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\DB\User',
            'enableAutoLogin' => true,
            'on afterLogin' => function ($event) {
                \app\models\Basket::getBasketFromUser();
            },
            'on afterLogout' => function ($event) {
                \app\models\Basket::clearBasket();
            },
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => $mail,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'about' => 'site/about',
                'contact' => 'site/contact',
                'kak-kupit' => 'site/howtobuy',
                'payment' => 'site/payment',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'register' => 'site/register',
                'recover' => 'site/recover',
                'reset/<hash>/<email>' => 'site/reset',
                'c/<slug>/<page:\d+>' => 'catalog/category',
                'c/<slug>' => 'catalog/category',
                'brands' => 'catalog/brands',
                'search/<page:\d+>' => 'catalog/search',
                'search' => 'catalog/search',
                'basket' => 'basket/index',
                'basket/remove/<slug>' => 'basket/remove',
                'checkout' => 'order/checkout',
                'orders' => 'user/orders',
                'admin' => 'admin/orders/index',
                'admin/order/<id:\d+>' => 'admin/orders/order',
                'admin/category/<id:\d+>' => 'admin/category/category',
                'admin/category' => 'admin/category/index',
                'admin/product/<id:\d+>' => 'admin/product/product',
                'admin/product' => 'admin/product/index',
                '<slug>' => 'catalog/product',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
