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
        if(!in_array('admin',json_decode(Yii::$app->user->identity->roles))){
            Yii::$app->session->addFlash('error','У вас нет доступа к админскому разделу');
            $this->redirect('/');
            return false;
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}