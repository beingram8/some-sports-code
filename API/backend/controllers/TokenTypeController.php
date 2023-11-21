<?php

namespace backend\controllers;

use common\models\TokenType;
use common\models\TokenTypeSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TokenTypeController implements the CRUD actions for TokenType model.
 */
class TokenTypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TokenType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TokenTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new TokenType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->general->setFlash('create', 'Token');
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
            Yii::$app->general->setFlash('update', 'Token');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    protected function findModel($id)
    {
        if (($model = TokenType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}