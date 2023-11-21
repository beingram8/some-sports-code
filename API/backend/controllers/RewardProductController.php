<?php

namespace backend\controllers;

use common\models\RewardProduct;
use common\models\RewardProductSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * RewardProductController implements the CRUD actions for RewardProduct model.
 */
class RewardProductController extends Controller
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
                        'actions' => ['create', 'index', 'update', 'delete', 'index', 'view', 'add-code', 'import', 'download', 'view-code'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all RewardProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RewardProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RewardProduct model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new RewardProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RewardProduct();

        if ($model->load(Yii::$app->request->post())) {
            
            if ($model->validate() && $model->save()) {
                Yii::$app->general->setFlash('create', 'Product');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RewardProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->general->setFlash('update', 'Product');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RewardProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->img->unlink($model->reward_img_url);
        $model->delete();

        Yii::$app->general->setFlash('delete', 'Product');
        return $this->redirect(['index']);

    }

    public function actionAddCode($id)
    {

        return $this->render('add-code', [
            'id' => $id,
        ]);
    }

    public function actionImport($id)
    {
        $model = new \app\models\ImportForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file) {
                $handle = fopen($model->file->tempName, "r");
                while (($fileop = fgetcsv($handle, 1000, ",")) !== false) {
                    $reward_code = new \common\models\RewardCode();
                    $reward_code->reward_id = $id;
                    $reward_code->reward_code = !empty($fileop[0]) ? $fileop[0] : "";

                    $reward_code->save();
                }
            }

            return $this->redirect(['index']);
        }
    }

    public function actionDownload()
    {
        $path = Yii::getAlias('@webroot') . '/reward-code.csv';
        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, 'Reward_code.csv');
        }
    }

    public function actionViewCode($id)
    {
        $product_name = Yii::$app->reward->getRewardName($id);

        $searchModel = new \common\models\RewardCodeSearch();
        $searchModel->reward_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view-code', [
            'name' => $product_name,
            'reward_id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the RewardProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RewardProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RewardProduct::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}