<?php

namespace frontend\modules\v1\controllers;

use common\models\Notification;
use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class NotificationController extends ActiveController
{
    public $modelClass = '';

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

        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['list', 'remove-all'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['list', 'remove-all', 'get-badge'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionGetBadge()
    {
        $count = \common\models\Notification::find()->where(['user_id' => Yii::$app->user->identity->id, 'is_read' => 'N'])->count();
        return ['status' => true, 'data' => ['count' => $count]];
    }
    public function actionList()
    {
        Notification::updateAll(['is_read' => 1], ['and', ['user_id' => Yii::$app->user->identity->id, 'is_read' => 'N']]);
        $search = new Notification();
        $search->attributes = Yii::$app->request->get();
        $data = $search->getItem();

        return ['status' => true, 'data' => $data];
    }

    public function actionRemoveAll()
    {
        Notification::deleteAll(['user_id' => \Yii::$app->user->id]);
        return [
            'status' => true,
            'data' => '',
        ];
    }
}
