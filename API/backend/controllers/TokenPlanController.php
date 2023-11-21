<?php

namespace backend\controllers;

use common\models\TokenPlan;
use common\models\TokenPlanSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TokenPlanController implements the CRUD actions for TokenPlan model.
 */
class TokenPlanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'update', 'create'],
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'create'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all TokenPlan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TokenPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TokenPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TokenPlan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->general->setFlash('create', 'Token Plan');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->general->setFlash('update', 'Token Plan');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    protected function findModel($id)
    {
        if (($model = TokenPlan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}