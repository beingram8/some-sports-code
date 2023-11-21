<?php

namespace frontend\modules\v1\controllers;

use common\models\TeasingRoom;
use common\models\TeasingRoomComment;
use common\models\TeasingRoomLike;
use common\models\TeasingRoomReported;
use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class TeasingRoomController extends ActiveController
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
            'actions' => [
                'add-post' => ['post'],
                'edit-post' => ['post'],
                'post-detail' => ['get'],
                'post-list' => ['get'],
                'delete-post' => ['get'],
                'like-post' => ['post'],
                'add-comment' => ['post'],
                'comment-list' => ['get'],
                'report-post' => ['post'],
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
            'post-detail',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['add-post', 'report-post', 'post-list', 'comment-list', 'like-post'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['add-post', 'report-post', 'post-list', 'comment-list', 'like-post', 'edit-post', 'delete-post'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionAddPost()
    {
        $model = new TeasingRoom();
        $model->user_id = \Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post())) {

            if ($model->is_video == 1) {
                $model->media = UploadedFile::getinstance($model, 'media');
                $model->media = Yii::$app->img->upload(UploadedFile::getInstance($model, 'media'), $s3_folder = "teasing_room", $size = 500, "", "video");
            } else {
                $model->media = UploadedFile::getinstance($model, 'media');
                $model->media = Yii::$app->img->upload(UploadedFile::getInstance($model, 'media'), $s3_folder = "teasing_room", $size = 500, "", "image");
            }

            // $model->thumb_media = UploadedFile::getinstance($model, 'thumb_media');
            // $model->thumb_media = Yii::$app->img->upload(UploadedFile::getInstance($model, 'thumb_media'), $s3_folder = "teasing_room", $size = 500, "", "image");

            $model->token = \Yii::$app->security->generateRandomString();
            if ($model->validate() && $model->save()) {
                return ['status' => true, 'data' => [
                    'is_animation' => true,
                ]];
            } else {
                return ['status' => false, 'message' => Yii::$app->general->error($model->errors)];
            }
        }
        return ['status' => false];
    }

    public function actionEditPost($id)
    {
        $model = \common\models\TeasingRoom::find()->where(['id' => $id, 'user_id' => \Yii::$app->user->id])->one();
        if (!empty($model)) {
            if ($model->load(Yii::$app->request->post())) {
                $model->caption = $model->caption;
                $model->save();
                return ['status' => true, 'data' => [
                    'id' => $model->id,
                    'username' => $model->userData->username,
                    'user_photo' => $model->userData->photo,
                    'thumb_media' => $model->thumb_media,
                    'media' => $model->media,
                    'caption' => $model->caption,
                    'likes' => $model->likes,
                    'created_at' => \Yii::$app->general->timeAgo($model->created_at),
                    'total_likes' => \common\models\TeasingRoomLike::find()->where(['teasing_id' => $model->id])->count(),
                    'total_comments' => \common\models\TeasingRoomComment::find()->where(['teasing_id' => $model->id])->count(),
                ],
                ];
            }
        } else {
            return ['status' => false, 'message' => 'No data found'];
        }
    }

    public function actionDeletePost($id)
    {
        $model = \common\models\TeasingRoom::find()->where(['id' => $id, 'user_id' => \Yii::$app->user->id])->one();
        if (!empty($model)) {
            \Yii::$app->img->unlink($model->thumb_media);
            \Yii::$app->img->unlink($model->media);
            if ($model->delete()) {
                return ['status' => true, 'message' => 'Post deleted successfully'];
            } else {
                return ['status' => false, 'message' => Yii::$app->general->error($model->errors)];
            }
        } else {
            return ['status' => false, 'message' => 'No data found'];
        }
    }

    public function actionPostDetail($token)
    {
        $post = \common\models\TeasingRoom::find()->where(['token' => $token])->andWhere(['is_active' => 1])->one();
        if ($post) {
            return ['status' => true, 'data' => [
                'id' => $post->id,
                'username' => $post->userData->username,
                'user_photo' => $post->userData->photo,
                'thumb_media' => $post->thumb_media,
                'media' => $post->media,
                'is_video' => $post->is_video,
                'caption' => $post->caption,
                'likes' => $post->likes,
                'created_at' => \Yii::$app->general->timeAgo($post->created_at),
                'total_likes' => \common\models\TeasingRoomLike::find()->where(['teasing_id' => $post->id])->count(),
                'total_comments' => \common\models\TeasingRoomComment::find()->where(['teasing_id' => $post->id])->count(),
            ],
            ];
        }
        return ['status' => false, 'message' => 'Post not found'];
    }

    public function actionPostList()
    {
        $model = new TeasingRoom();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getList();
        return ['status' => true, 'data' => $data];
    }

    public function actionLikePost($id)
    {
        $post = \common\models\TeasingRoom::find()->where(['id' => $id])->andWhere(['is_active' => 1])->one();
        if (!empty($post)) {
            $checkLike = TeasingRoomLike::find()->where(['teasing_id' => $id])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one();
            if ($checkLike) {
                $checkLike->delete();
                return ['status' => true, 'message' => 'Post disliked'];
            }
            $model = new TeasingRoomLike();
            $model->teasing_id = $id;
            $model->user_id = \Yii::$app->user->identity->id;
            if ($model->save()) {
                $check_token = \common\models\UserTokenTransaction::find()->where(['teasing_like_id' => $id, 'user_id' => \Yii::$app->user->identity->id])->count();
                if ($check_token == 0) {
                    \Yii::$app->token->teasingTokenTransaction($id, true);
                    \Yii::$app->token->updateUserToken(Yii::$app->user->identity->id, \Yii::$app->token->getTokenValue('teasing_token'));
                    return ['status' => true, 'message' => 'Post liked', 'data' => ['is_animation' => true]];
                } else {
                    return ['status' => true, 'message' => 'Post liked', 'data' => ['is_animation' => false]];
                }
            }
        }
        return ['status' => false, 'message' => 'Post not found'];
    }

    public function actionAddComment()
    {
        $data = \Yii::$app->request->post();
        if (empty($data['id']) || empty($data['comment'])) {
            return ['status' => false, 'message' => 'Missing id and comment parameter'];
        }
        $post = \common\models\TeasingRoom::find()->where(['id' => $data['id']])->andWhere(['is_active' => 1])->one();
        if ($post) {
            $model = new TeasingRoomComment();
            $model->teasing_id = $data['id'];
            $model->user_id = \Yii::$app->user->identity->id;
            $model->comment = $data['comment'];
            if ($model->save()) {
                $check_token = \common\models\UserTokenTransaction::find()->where(['teasing_comment_id' => $model->teasing_id, 'user_id' => \Yii::$app->user->identity->id])->count();
                if ($check_token == 0) {
                    \Yii::$app->token->teasingTokenTransaction($model->teasing_id, false);
                    \Yii::$app->token->updateUserToken(Yii::$app->user->identity->id, \Yii::$app->token->getTokenValue('teasing_token'));
                    return ['status' => true, 'message' => 'Comment added', 'data' => ['is_animation' => true]];
                } else {
                    return ['status' => true, 'message' => 'Comment added', 'data' => ['is_animation' => false]];
                }
            }
        }
        return ['status' => false, 'message' => 'Post not found'];
    }

    public function actionCommentList($id)
    {
        $model = new TeasingRoomComment();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getList($id);
        return ['status' => true, 'data' => $data];
    }

    public function actionReportPost()
    {
        $model = new TeasingRoomReported();
        $model->reported_user_id = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return ['status' => true, 'message' => 'Stanza presa in giro segnalata'];
            }
        } else {
            return ['status' => false, 'message' => \Yii::$app->general->error($model->errors)];
        }
    }
}
