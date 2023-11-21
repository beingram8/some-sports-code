<?php
namespace frontend\modules\v1\controllers;

use common\models\News;
use common\models\NewsComment;
use common\models\NewsLike;
use common\models\UserTokenTransaction;
use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class NewsController extends ActiveController
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
                'list' => ['post'],
                'like' => ['post'],
                'comment' => ['post'],
                'detail' => ['get'],
                'comment-list' => ['get'],
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
            'detail-for-guest',
            'comment-list',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['like', 'comment', 'detail', 'list-for-user'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['like', 'comment', 'detail', 'list-for-user'],
                    'roles' => ['@'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionList()
    {
        $model = new News();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getNewsList();
        return ['status' => true, 'data' => $data];
    }

    public function actionListForUser()
    {
        $model = new News();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getNewsList();
        return ['status' => true, 'data' => $data];
    }

    public function actionLike()
    {
        $animation = false;
        $model = new NewsLike();
        $post = Yii::$app->request->post();
        if (empty($post['id'])) {
            return ['status' => false, 'message' => \Yii::t('app', 'ID parametro mancante')];
        }
        $model->user_id = Yii::$app->user->identity->id;
        $model->news_id = $post['id'];
        $news = NewsLike::find()->where(['news_id' => $model->news_id, 'user_id' => Yii::$app->user->identity->id])->one();
        if (!empty($news) && $news->is_like == 1) {
            NewsLike::deleteAll(['news_id' => $model->news_id, 'user_id' => Yii::$app->user->identity->id]);
            return ['status' => true, 'message' => 'Non mi è piaciuto'];
        } else {
            $model->is_like = 1;

            //assign token
            $tokenId = Yii::$app->token->getTokenValue('like_comment_news');
            $checkToken = UserTokenTransaction::find()->where(['user_id' => Yii::$app->user->identity->id])
                ->andWhere(['news_like_id' => $model->news_id])
                ->one();
            if (empty($checkToken)) {
                $animation = true;
                Yii::$app->token->newsTokenTransaction($model->news_id, true);
                Yii::$app->token->updateUserToken(Yii::$app->user->identity->id, \Yii::$app->token->getTokenValue('like_comment_news'));
            }

            if ($model->validate() && $model->save()) {
                return ['status' => true, 'message' => 'È piaciuto', 'data' => ['is_animation' => $animation]];
            }
        }
        return ['status' => false, 'message' => Yii::$app->general->error($model->errors)];
    }

    public function actionComment()
    {
        $animation = false;
        $model = new NewsComment();
        $model->user_id = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                //assign token
                $tokenId = Yii::$app->token->getTokenValue('like_comment_news');
                $checkToken = UserTokenTransaction::find()->where(['user_id' => Yii::$app->user->identity->id])
                    ->andWhere(['news_comment_id' => $model->news_id])
                    ->one();
                if (empty($checkToken)) {
                    $animation = true;
                    Yii::$app->token->newsTokenTransaction($model->news_id, false);
                    Yii::$app->token->updateUserToken(Yii::$app->user->identity->id, \Yii::$app->token->getTokenValue('like_comment_news'));
                }
                return ['status' => true, 'message' => \Yii::t('app', 'Commento aggiunto'), 'data' => ['is_animation' => $animation]];
            }
        } else {
            return ['status' => false, 'message' => Yii::$app->general->error($model->errors)];
        }
        return ['status' => false, 'message' => $model->errors];
    }

    public function actionCommentList($news_id)
    {
        $model = new NewsComment();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getCommentList($news_id);
        return ['status' => true, 'data' => $data];
    }

    public function actionDetail($slug, $news_id)
    {
        $check_news_like = \common\models\NewsLike::find()->where(['user_id' => \Yii::$app->user->id, 'news_id' => $news_id])->one();
        $is_like = !empty($check_news_like) ? true : false;

        $news = News::find()->where(['slug' => $slug, 'is_active' => 1])->one();
        if (!empty($news)) {
            $news['created_at'] = \Yii::$app->time->asDatetime($news['created_at']);
            return ['status' => true, 'data' => [
                'id' => $news->id,
                'title' => $news->title,
                'small_description' => $news->small_description,
                'body' => $news->body,
                'thumb_img' => $news->thumb_img,
                'main_img' => $news->main_img,
                'is_active' => $news->is_active,
                'is_general' => $news->is_general,
                'slug' => $news->slug,
                'created_at' => $news->created_at,
                'total_likes' => News::getLikeCount($news->id),
                'is_like' => $is_like,
            ]];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Nessuna notizia trovata.')];
        }
    }

    public function actionDetailForGuest($slug)
    {
        $news = News::find()->where(['slug' => $slug, 'is_active' => 1])->one();
        if (!empty($news)) {
            $news['created_at'] = \Yii::$app->time->asDatetime($news['created_at']);
            return ['status' => true, 'data' => [
                'id' => $news->id,
                'title' => $news->title,
                'small_description' => $news->small_description,
                'body' => $news->body,
                'thumb_img' => $news->thumb_img,
                'main_img' => $news->main_img,
                'is_active' => $news->is_active,
                'is_general' => $news->is_general,
                'slug' => $news->slug,
                'created_at' => $news->created_at,
                'total_likes' => News::getLikeCount($news->id),
                'is_like' => false,
            ]];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Nessuna notizia trovata.')];
        }
    }
}
