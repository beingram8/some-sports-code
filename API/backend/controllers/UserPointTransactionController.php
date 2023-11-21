<?php

namespace backend\controllers;

use common\models\UserPointTransaction;
use common\models\UserPointTransactionSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserPointTransactionController implements the CRUD actions for UserPointTransaction model.
 */
class UserPointTransactionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'search-user'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'search-user'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all UserPointTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserPointTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserPointTransaction model.
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

    public function actionSearchUser($term)
    {
        if (\Yii::$app->request->isAjax) {
            $results = [];
            if (is_numeric($term)) {
                /** @var User $model */
                $model = \common\models\UserData::find()->where(['user_id' => $term])
                    ->asArray()->one();

                if ($model) {
                    $results[] = [
                        'id' => $model['user_id'],
                        'label' => $model['first_name'] . ' ' . $model['last_name'] . '',
                    ];
                }
            } else {
                $q = addslashes($term);
                $users = \common\models\UserData::find()->where("(`first_name` like '%{$q}%')")->asArray()
                    ->all();
                foreach ($users as $model) {
                    $results[] = [
                        'id' => $model['user_id'],
                        'label' => $model['first_name'] . ' ' . $model['last_name'] . '',
                    ];
                }
            }
            echo \yii\helpers\Json::encode($results);die;
        }
    }

    /**
     * Creates a new UserPointTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($user_id)
    {
        $model = new UserPointTransaction();

        if ($model->load(Yii::$app->request->post())) {
            $model->points = $model->transaction_type == 1 ? $model->points : '-' . $model->points;
            $model->user_id = $user_id;
            if ($model->validate() && $model->save()) {
                Yii::$app->userData->sum_of_point($model->user_id);
                Yii::$app->general->setFlash('create', 'User point');
                return $this->redirect(['index', 'UserPointTransactionSearch[user_id]' => $user_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserPointTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->points = $model->transaction_type == 1 ? $model->points : str_replace("-", "", $model->points);

        if ($model->load(Yii::$app->request->post())) {
            $model->points = $model->transaction_type == 1 ? $model->points : '-' . $model->points;
            if ($model->validate() && $model->save()) {
                Yii::$app->userData->sum_of_point($model->user_id);
                Yii::$app->general->setFlash('update', 'User point');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the UserPointTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserPointTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserPointTransaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}