<?php

namespace backend\controllers;

use common\models\Season;
use common\models\SeasonSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MsSystemController implements the CRUD actions for MsSystemparameters model.
 */
class SeasonController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'update-expire'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Season models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeasonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate()
    {
        $model = new Season();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Season model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionUpdateExpire()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            $data['option'] = $data['option'] == 0 ? 1 : 0;
            Season::updateAll(['is_expired' => $data['option']], ['season' => $data['season']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    /**
     * Finds the Season model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Season the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Season::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}