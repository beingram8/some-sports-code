<?php

namespace frontend\modules\v1\controllers;

use common\models\UserTokenTransaction;
use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class VideoController extends ActiveController
{
    public $modelClass = 'common\models\Video';
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
                'stream-watched' => ['get'],
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
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['details-for-users', 'stream-watched'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['details-for-users', 'stream-watched'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionList()
    {
        $model = new \common\models\Video();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getList();
        return ['status' => true, 'data' => $data];
    }

    public function actionDetails($id)
    {
        $videoDetails = \common\models\Video::find()->where(['id' => $id])->one();
        if (!empty($videoDetails)) {
            return ['status' => true, 'data' => [
                'id' => $videoDetails->id,
                'title' => $videoDetails->title,
                'video_url' => $videoDetails->is_external == 1 ? $videoDetails->external_link : $videoDetails->video_url,
                'thumb_img' => $videoDetails->thumb_img,
                'description' => $videoDetails->description,
            ]];
        } else {
            return ['status' => true, 'data' => \Yii::t('app', 'Nessun dato trovato')];
        }
    }

    public function actionDetailsForUsers($id, $is_watch = 0)
    {
        $is_animation = false;
        $videoDetails = \common\models\Video::find()->where(['id' => $id])->one();
        if (!empty($videoDetails)) {
            if ($is_watch == 1) {
                $tokenValue = Yii::$app->token->getTokenValue('video_watch');
                $checkToken = UserTokenTransaction::find()->where(['user_id' => Yii::$app->user->identity->id])
                    ->andWhere(['video_id' => $videoDetails->id])
                    ->one();
                if (empty($checkToken)) {
                    $is_animation = true;
                    Yii::$app->token->videoTokenTransaction($videoDetails->id);
                    Yii::$app->token->updateUserToken(Yii::$app->user->identity->id, $tokenValue);
                }
            }

            return ['status' => true, 'data' => [
                'id' => $videoDetails->id,
                'title' => $videoDetails->title,
                'video_url' => $videoDetails->is_external == 1 ? $videoDetails->external_link : $videoDetails->video_url,
                'thumb_img' => $videoDetails->thumb_img,
                'description' => $videoDetails->description,
                'is_animation' => $is_animation,
            ]];
        } else {
            return ['status' => true, 'data' => \Yii::t('app', 'Nessun dato trovato')];
        }
    }

    public function actionStreamWatched($stream_id, $is_watch = 0)
    {
        $is_animation = false;
        if ($is_watch == 1) {
            $tokenValue = Yii::$app->token->getTokenValue('watching_live_stream');
            $checkToken = UserTokenTransaction::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->andWhere(['stream_id' => $stream_id])
                ->one();
            if (empty($checkToken)) {
                $is_animation = true;
                $user_id = \Yii::$app->user->id;
                $assign_user_token = new \common\models\UserTokenTransaction();
                $assign_user_token->user_id = $user_id;
                $assign_user_token->transaction_type = 10;
                $assign_user_token->token_type_id = Yii::$app->token->getTokenId('watching_live_stream');
                $assign_user_token->token = Yii::$app->token->getTokenValue('watching_live_stream');
                $assign_user_token->created_by = $user_id;
                $assign_user_token->stream_id = $stream_id;
                $assign_user_token->remark = \Yii::t('app', 'For participating in a LIVE');
                if ($assign_user_token->save()) {
                    Yii::$app->token->updateUserToken(Yii::$app->user->identity->id, $tokenValue);
                    return ['status' => true, 'data' => ['is_animation' => true]];
                }
            }
        }
        return ['status' => false];
    }

}
