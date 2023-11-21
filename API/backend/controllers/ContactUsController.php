<?php

namespace backend\controllers;

use Yii;
use common\models\ContactUs;
use common\models\ContactUsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactUsController implements the CRUD actions for ContactUs model.
 */
class ContactUsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'update-status'],
                'rules' => [
                    [
                        'actions' => ['index', 'update-status'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all ContactUs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactUsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            ContactUs::updateAll(['status' => $data['option']], ['id' => $data['id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    protected function findModel($id)
    {
        if (($model = ContactUs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
