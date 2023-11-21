<?php

namespace backend\controllers;

use common\models\Video;
use common\models\VideoSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends Controller
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
                        'actions' => ['create', 'index', 'update', 'delete', 'index', 'view'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

            if (\yii\web\UploadedFile::getInstance($model, 'video_url')) {
                $model->video_url = Yii::$app->img->upload(UploadedFile::getInstance($model, 'video_url'), $s3_folder = "videos", $size = 500, "video", false);
            }

            if ($model->validate() && $model->save()) {
                \Yii::$app->notification->savePush(
                    $model->title,
                    'Guarda il nuovo video e guadagna Fan Coins',
                    'video',
                    ['video_id' => $model->id, 'type' => 'video'],
                    \Yii::$app->security->generateRandomString(12),
                    $per_page = 1000
                );
                Yii::$app->general->setFlash('create', 'Video');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_video = $model->video_url;

        if ($model->load(Yii::$app->request->post())) {

            if (\yii\web\UploadedFile::getInstance($model, 'video_url')) {
                $model->video_url = Yii::$app->img->upload(UploadedFile::getInstance($model, 'video_url'), $s3_folder = "videos", $size = 500, $old_video, "video", false);
            } else {
                $model->video_url = $old_video;
            }

            if ($model->is_external == 1) {
                $model->video_url = '';
            } else {
                $model->external_link = '';
            }
            Yii::$app->general->setFlash('update', 'Video');

            if ($model->validate() && $model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Video model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->img->unlink($model->video_url);
        Yii::$app->img->unlink($model->thumb_img);

        $model->delete();
        Yii::$app->general->setFlash('delete', 'Video');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
