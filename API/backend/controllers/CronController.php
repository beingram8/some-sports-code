<?php

namespace backend\controllers;

use common\models\CronSchedule;
use common\models\CronScheduleSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CronController implements the CRUD actions for CronSchedule model.
 */
class CronController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'delete', 'delete-all'],
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'delete-all'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CronSchedule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CronScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionDeleteAll()
    {
        Yii::$app->db->createCommand()->truncateTable('cron_schedule')->execute();
        return $this->redirect(['index']);
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = CronSchedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}