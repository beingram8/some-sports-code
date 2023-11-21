<?php

namespace frontend\modules\v1\controllers;

use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class CmsController extends ActiveController
{
    public $modelClass = 'common\models\Cms';
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'cms-detail',
        ];
        return $behaviors;
    }
    public function actionCmsDetail($slug, $lang)
    {
        $user = \Yii::$app->user->identity;

        $data = \common\models\Cms::find()->where(['slug' => $slug])->andWhere(['status' => 1])->andWhere(['language' => $lang])->one();
        if (!empty($data)) {
            return ['status' => true, 'data' => [
                'html_body' => $data->html_body,
                'app_body' => $data->app_body,
            ]];
        } else {
            return ['status' => true, 'data' => $user];
        }
    }

}