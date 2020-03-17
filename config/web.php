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
                'login' => 'site/login',
                'logout' => 'site/logout',
                'register' => 'site/register',
                'recover' => 'site/recover',
                'reset/<hash>/<email>' => 'site/reset',
                'category/<slug>/<page:\d+>' => 'catalog/category',
                'category/<slug>' => 'catalog/category',
                'product/<slug>' => 'catalog/product',
                'brand/<slug>/<page:\d+>' => 'catalog/brand',
                'brand/<slug>' => 'catalog/brand',
                'brands' => 'catalog/brands',
                'search/<page:\d+>' => 'catalog/search',
                'search' => 'catalog/search',
                'basket' => 'basket/index',
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
