<?php

namespace backend\controllers;

use common\models\News;
use common\models\NewsAssignedTeam;
use common\models\NewsComment;
use common\models\NewsLike;
use common\models\NewsSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'delete-comment', 'index', 'update', 'delete', 'index', 'view'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $commentQuery = NewsComment::find()->where(['news_id' => $id]);
        $commentProvider = new ActiveDataProvider([
            'query' => $commentQuery,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'comment-page',
            ],
        ]);

        $likesQuery = NewsLike::find()->where(['news_id' => $id]);
        $likesProvider = new ActiveDataProvider([
            'query' => $likesQuery,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'like-page',
            ],
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'commentProvider' => $commentProvider,
            'likeProvider' => $likesProvider,
        ]);
    }

    public function createSlug($title)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '_', $title);
        return $slug;
    }
    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->team)) {
                $model->is_general = 0;
                $model->slug = $this->createSlug(strtolower($model->title));
                if ($model->validate() && $model->save()) {
                    $this->assignNews($model->team, $model->id);
                    if ($model->is_active == 1) {
                        \Yii::$app->notification->savePush(
                            $model->title,
                            $model->small_description,
                            'news',
                            ['news_id' => $model->id, 'slug' => $model->slug, 'type' => 'news'],
                            \Yii::$app->security->generateRandomString(12),
                            $per_page = 1000
                        );
                    }
                    Yii::$app->general->setFlash('create', 'News');
                    return $this->redirect(['index']);
                }
            } else {
                $model->is_general = 1;
                $model->slug = $this->createSlug(strtolower($model->title));
                if ($model->validate() && $model->save()) {
                    if ($model->is_active == 1) {
                        \Yii::$app->notification->savePush(
                            $model->title,
                            $model->small_description,
                            'news',
                            ['news_id' => $model->id, 'slug' => $model->slug, 'type' => 'news'],
                            \Yii::$app->security->generateRandomString(12),
                            $per_page = 1000
                        );
                    }
                    Yii::$app->general->setFlash('create', 'News');
                    return $this->redirect(['index']);
                }
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->team = \yii\helpers\ArrayHelper::getcolumn($model->newsAssignedTeams, 'team_id');
        $checkNotification = \common\models\Notification::find()
            ->where(['type' => 'news'])
            ->andFilterWhere(['like', 'data', '"news_id": ' . $id])
            ->count();
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->team)) {
                $model->is_general = 0;
                if ($model->validate() && $model->save()) {
                    NewsAssignedTeam::deleteAll(['news_id' => $model->id]);
                    $this->assignNews($model->team, $model->id);

                    if ($model->is_active == 1 && $checkNotification == 0) {
                        \Yii::$app->notification->savePush(
                            $model->title,
                            $model->small_description,
                            'news',
                            ['news_id' => $model->id, 'slug' => $model->slug, 'type' => 'news'],
                            \Yii::$app->security->generateRandomString(12),
                            $per_page = 1000
                        );
                    }
                    Yii::$app->general->setFlash('update', 'News');
                    return $this->redirect(['index']);
                }
            } else {
                NewsAssignedTeam::deleteAll(['news_id' => $model->id]);
                $model->is_general = 1;
                if ($model->validate() && $model->save()) {
                    if ($model->is_active == 1 && $checkNotification == 0) {
                        \Yii::$app->notification->savePush(
                            $model->title,
                            $model->small_description,
                            'news',
                            ['news_id' => $model->id, 'slug' => $model->slug, 'type' => 'news'],
                            \Yii::$app->security->generateRandomString(12),
                            $per_page = 1000
                        );
                    }
                    Yii::$app->general->setFlash('update', 'News');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    //Store team_id with news in news_assigned_team
    public function assignNews($team, $news_id)
    {
        foreach ($team as $team_id) {
            $model = new NewsAssignedTeam();
            $model->news_id = $news_id;
            $model->team_id = $team_id;
            $model->save();
        }
        return true;
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->img->unlink($model->thumb_img);
        Yii::$app->img->unlink($model->main_img);
        $model->delete();
        Yii::$app->general->setFlash('delete', 'News');
        return $this->redirect(['index']);
    }
    public function actionDeleteComment($comment_id)
    {
        $model = \common\models\NewsComment::findOne($comment_id);
        if ($model) {
            $news_id = $model->news_id;
            $model->delete();
            return $this->redirect(['view', 'id' => $news_id]);

        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}