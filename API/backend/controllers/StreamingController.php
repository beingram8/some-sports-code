<?php

namespace backend\controllers;

use common\models\Streaming;
use common\models\StreamingSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * StreamingController implements the CRUD actions for Streaming model.
 */
class StreamingController extends Controller
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
     * Lists all Streaming models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StreamingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Streaming model.
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
     * Creates a new Streaming model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Streaming();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

            if (UploadedFile::getInstance($model, 'video')) {
                $model->video = UploadedFile::getInstance($model, 'video');
                $model->video = Yii::$app->img->upload(UploadedFile::getInstance($model, 'video'), $s3_folder = "stream", $size = 500, "", "video");
            }

            if ($model->validate() && $model->save()) {
                if ($model->is_live) {
                    \Yii::$app->notification->savePush(
                        'Fan Rating è in diretta ora.',
                        $model->title,
                        'live_stream',
                        ['streaming_id' => $model->id, 'type' => 'streaming'],
                        \Yii::$app->security->generateRandomString(12),
                        $per_page = 1000
                    );
                }

                Yii::$app->general->setFlash('create', 'Streaming');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Streaming model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'create';
        $oldVideo = $model->video;
        if ($model->load(Yii::$app->request->post())) {

            if (UploadedFile::getInstance($model, 'video')) {
                $model->video = UploadedFile::getInstance($model, 'video');
                $model->video = Yii::$app->img->upload(UploadedFile::getInstance($model, 'video'), $s3_folder = "stream", $size = 500, "", "video", false);
            } else {
                $model->video = $oldVideo;
            }

            if ($model->validate() && $model->save()) {
                if ($model->is_live) {
                    \Yii::$app->notification->savePush(
                        'Fan Rating è in diretta ora.',
                        $model->title,
                        'live_stream',
                        ['streaming_id' => $model->id, 'type' => 'streaming'],
                        \Yii::$app->security->generateRandomString(12),
                        $per_page = 1000
                    );
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Streaming model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            Yii::$app->img->unlink($model->thumb_img);
            Yii::$app->img->unlink($model->video);
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Streaming model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Streaming the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Streaming::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
