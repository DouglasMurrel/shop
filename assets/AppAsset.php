<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css',
        'css/cssfix.css',
        '//use.fontawesome.com/releases/v5.0.13/css/all.css',
    ];
    public $js = [
        '//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js',
        '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
