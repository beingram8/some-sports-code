<?php

namespace frontend\modules\v1\controllers;

use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class ProductController extends ActiveController
{
    public $modelClass = 'common\models\RewardProduct';

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
            'list',
            'details',
        ];
        return $behaviors;
    }

    public function actionList()
    {
        $model = new \common\models\RewardCategory();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getProductList();
        return ['status' => true, 'data' => $data];
    }

    public function actionDetails($reward_id)
    {
        $rewardDetails = \common\models\RewardProduct::find()->where(['id' => $reward_id])->one();
        if (!empty($rewardDetails)) {
            return ['status' => true, 'data' => [
                'id' => $rewardDetails->id,
                'title' => $rewardDetails->name,
                'reward_img_url' => $rewardDetails->reward_img_url,
                'reward_description' => $rewardDetails->reward_description,
                'description' => $rewardDetails->description,
                'token' => $rewardDetails->buying_token,
            ]];
        } else {
            return ['status' => true, 'data' => \Yii::t('app','Nessun dato trovato')];
        }
    }
}