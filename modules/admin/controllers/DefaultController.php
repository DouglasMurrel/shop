<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            Yii::$app->session->addFlash('error','У вас нет доступа к админскому разделу');
            $this->redirect('/');
            return false;
        }
        if(!Yii::$app->user->identity->isAdmin()){
            Yii::$app->session->addFlash('error','У вас нет доступа к админскому разделу');
            $this->redirect('/');
            return false;
        }
        return parent::beforeAction($action);
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('orders/index');
    }
}
