<?php

namespace app\modules\admin;

use Yii;
use yii\web\ErrorHandler;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::configure($this, [
            'defaultRoute'=>'order/index',
            'components' => [
                'errorHandler' => [
                    'class' => ErrorHandler::class,
                    'errorAction' => 'admin/default/error'
                ]
            ],
        ]);
        $handler = $this->get('errorHandler');
        Yii::$app->set('errorHandler', $handler);
        $handler->register();
        // custom initialization code goes here
    }
}
