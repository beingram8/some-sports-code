<?php

namespace backend\controllers;

use common\models\MsSystemparameters;
use common\models\MsSystemparametersSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MsSystemController implements the CRUD actions for MsSystemparameters model.
 */
class SystemController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    public function actionPaymentMethod()
    {

        if (!empty($_POST['DynamicModel']) && !empty($_POST['method_name'])) {
            $model = \common\models\MsCreditBuyPaymentMethod::find()->where(['method_name' => $_POST['method_name']])->one();
            if (empty($model)) {
                \Yii::$app->general->throwError(\Yii::t('app', 'Invalid method'));
            }
            $model->json = json_encode($_POST['DynamicModel']);
            if ($model->save()) {
                Yii::$app->session->setFlash('method_success', \Yii::t('app', 'Payment method has been updated successfully.'));

                return $this->redirect(['payment-method']);
            }
        }
        return $this->render('payment-method', [
            'methods' => \common\models\MsCreditBuyPaymentMethod::find()->where(1)->asArray()->all(),
        ]);
    }
    /**
     * Lists all MsSystemparameters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MsSystemparametersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate()
    {
        $model = new MsSystemparameters();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MsSystemparameters model.
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

    /**
     * Finds the MsSystemparameters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsSystemparameters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsSystemparameters::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}