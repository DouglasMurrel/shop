<?php

namespace app\controllers;

use app\models\DB\Brand;
use app\models\DB\Product;
use app\models\DB\User;
use app\models\Forms\RecoverForm;
use app\models\Forms\RegisterForm;
use app\models\Forms\ResetForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Forms\LoginForm;
use app\models\Forms\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($event)
    {
        Yii::$app->view->title = Yii::$app->params['defaultTitle'];
        Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['defaultDescription']],'description');
        Yii::$app->view->registerMetaTag(['name' => 'keywords','content' => Yii::$app->params['defaultKeywords']],'keywords');
        return parent::beforeAction($event);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLogin = new LoginForm();
        $modelRegister = new RegisterForm();
        $modelRecover = new RecoverForm();
        if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
            return $this->goBack();
        }

        $modelLogin->password = '';
        return $this->render('login', [
            'modelLogin' => $modelLogin,
            'modelRegister' => $modelRegister,
            'modelRecover' => $modelRecover,
            'active' => 'login'
        ]);
    }

    /**
     * Register action.
     *
     * @return Response|string|array
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLogin = new LoginForm();
        $modelRegister = new RegisterForm();
        $modelRecover = new RecoverForm();

        if (Yii::$app->request->isAjax && $modelRegister->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelRegister);
        }

        if ($modelRegister->load(Yii::$app->request->post()) && $modelRegister->register()) {
            return $this->goBack();
        }

        $modelRegister->password = '';
        $modelRegister->password2 = '';
        return $this->render('login', [
            'modelLogin' => $modelLogin,
            'modelRegister' => $modelRegister,
            'modelRecover' => $modelRecover,
            'active' => 'register'
        ]);
    }

    /**
     * Recover action.
     *
     * @return Response|array|string
     */
    public function actionRecover()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLogin = new LoginForm();
        $modelRegister = new RegisterForm();
        $modelRecover = new RecoverForm();
        if (Yii::$app->request->isAjax && $modelRecover->load(Yii::$app->request->post()) && Yii::$app->request->post('ajax')=='recover-form') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelRecover);
        }
        if ($modelRecover->load(Yii::$app->request->post())){
            if ($modelRecover->sendmail()) {
                return $modelRecover->confirmation(true);
            }else{
                return $modelRecover->confirmation(false);
            }
        }

        $modelLogin->password = '';
        return $this->render('login', [
            'modelLogin' => $modelLogin,
            'modelRegister' => $modelRegister,
            'modelRecover' => $modelRecover,
            'active' => 'recover'
        ]);
    }

    /**
     * Reset password action.
     *
     * @return Response|string|array
     */
    public function actionReset()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ResetForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && Yii::$app->request->post('ajax')=='reset-form') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $hash = Yii::$app->request->get('hash');
        $email = Yii::$app->request->get('email');
        $hash1 = md5($hash.Yii::$app->params['salt']);
        $user = User::findByUsername($email);
        $hash2 = $user->passwordHash;
        if($hash1==$hash2){
            if ($model->load(Yii::$app->request->post())) {
                $model->email = $email;
                return $model->reset();
            }

            $model->password = '';
            return $this->render('reset', [
                'model' => $model,
                'email' => $email,
            ]);
        }

        return $this->goHome();
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
