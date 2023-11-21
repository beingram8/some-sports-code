<?php
namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $layout = "main";
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'account'],
                'rules' => [

                    [
                        'actions' => ['logout', 'account', 'index'],
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
    public function actionTest()
    {
        $start_day_time = strtotime(date('Y-m-d', time()));
        $end_day_time = time();
        $matches = \common\models\SeasonMatch::find()
            ->where(['>=', 'match_timestamp', $start_day_time])
            ->andWhere(['<=', 'match_timestamp', $end_day_time])
            ->andWhere(['is_vote_enabled' => 0])
            ->all();
        print($matches);die;
    }
    public function actionIndex()
    {

        return true;
    }

}