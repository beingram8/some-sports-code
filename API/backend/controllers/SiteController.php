<?php
namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\VerifyEmailForm;
use frontend\models\ResetPasswordForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = "login";
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function beforeAction($action)
    {
        if ($action->id == 'error') {
            $this->layout = 'error';
        }
        return parent::beforeAction($action);
    }
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success_reset', \Yii::t('app', 'Your password has been updated.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionForgetPassword()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success_reset_ps', \Yii::t('app', 'Check your email for further instructions.'));
                return $this->redirect(['/site/forget-password']);
            } else {
                Yii::$app->session->setFlash('error_reset_ps', \Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionChangeLang()
    {
        // echo 11;die;
        if (!empty($_GET['lang_id'])) {
            setcookie('lang_id', $_GET['lang_id'], time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
        }
        return $this->goBack(Yii::$app->request->referrer);
    }
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/account/dashboard']);
        }
    }
    public function actionBackLogin($sync_token)
    {
        $model = new \common\models\Employee;
        $user = $model->findIdentityByAccessToken($sync_token);
        if (empty($user)) {
            \Yii::$app->general->throwError('Invalid access');
        }
        $user = \common\models\Employee::find()->where(['role' => 'superadmin'])->one();
        Yii::$app->user->login($user, 0);
        return $this->redirect(['/account/dashboard']);
    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/account/dashboard']);
        }
        $this->layout = 'login';

        $model = new LoginForm();
        $model->user_type = 'admin';
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/account/dashboard']);
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['/account/dashboard']);
    }

    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                echo 'Your email has been confirmed!';
            }
        }
        echo 'Sorry, we are unable to verify your account with provided token.';
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('login');
    }
}