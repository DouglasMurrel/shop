<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller{
    public function beforeAction($event)
    {
        Yii::$app->view->title = Yii::$app->params['defaultTitle'];
        Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['defaultDescription']],'description');
        Yii::$app->view->registerMetaTag(['name' => 'keywords','content' => Yii::$app->params['defaultKeywords']],'keywords');
        return parent::beforeAction($event);
    }
}
