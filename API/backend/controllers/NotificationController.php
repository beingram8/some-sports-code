<?php

namespace backend\controllers;

use common\models\Notification;
use common\models\NotificationSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller
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
                        'actions' => ['create', 'index', 'users', 'teams', 'delete', 'view'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notification model.
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

    public function actionUsers()
    {
        $q = $_GET['q'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; //restituisco json
        $data = \common\models\UserUuid::find()->select(['DISTINCT(user_uuid.user_id) as user_id', 'CONCAT(user_data.first_name," ",user_data.last_name) as name'])
            ->joinWith(['userData'])
            ->where(['or', ['LIKE', 'first_name', $q], ['LIKE', 'last_name', $q]])
            ->asArray()->all();

        $d = [];
        foreach ($data as $k => $ele) {
            $d[$k]['id'] = $ele['user_id'];
            $d[$k]['text'] = $ele['name'];
        }
        $out['results'] = $d;
        return $out;
    }

    public function actionTeams()
    {
        $q = $_GET['t'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; //restituisco json
        $data = \common\models\SeasonTeam::find()
            ->where(['is_active' => 1])
            ->andWhere(['like', 'name', $q])
            ->asArray()->all();
        $d = [];
        foreach ($data as $k => $ele) {
            $d[$k]['id'] = $ele['id'];
            $d[$k]['text'] = $ele['name'];
        }
        $out['results'] = $d;
        return $out;
    }

    /**
     * Creates a new Notification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notification();

        if ($model->load(Yii::$app->request->post())) {

            \Yii::$app->notification->savePushByAdmin(
                $model->title,
                $model->message,
                'admin',
                ['type' => 'admin'],
                \Yii::$app->security->generateRandomString(12),
                $model->team_id,
                $model->user_ids
            );

            return $this->redirect('index');

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Notification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Notification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
