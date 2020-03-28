<?php
namespace app\modules\admin;
use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@app/modules/admin/web/assets';
    public $css = [
//        'css/style.css',
    ];
    public $js = [
        'js/admin.js'
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}