<?php

namespace backend\controllers;

use common\models\User;
use common\models\UserSearch;
use common\models\UserTokenTransactionSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['update-document', 'document', 'create', 'index', 'update', 'delete', 'view', 'update-status', 'transaction-detail', 'add-token', 'payment-details'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        // // send notify
        // $response = \Yii::$app->push->send(
        //     array(
        //         'user_id' => 6765,
        //         'from_user_id' => 1,
        //         'title' => 'testssssss',
        //         'message' => 'ssssss',
        //         'type' => 'admin',
        //     )
        // );
        // print_r($response);die;
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDocument()
    {
        $searchModel = new \common\models\ParentConfirmationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('document', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
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

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!empty($model->userData->photo)) {
            \Yii::$app->img->unlink($model->userData->photo);
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            User::updateAll(['status' => $data['value']], ['id' => $data['id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    public function actionUpdateDocument()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            \common\models\ParentConfirmation::updateAll(['is_confirm' => $data['value']], ['id' => $data['id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    public function actionTransactionDetail()
    {
        $searchModel = new UserTokenTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('transaction-detail', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddToken($user_id)
    {
        $check_user = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        $model = new \common\models\UserTokenTransaction();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = $user_id;
            if ($model->transaction_type == 20 && $check_user->token < $model->token) {
                $model->addError('token', 'Invalid Token value');
                return $this->render('add-token', [
                    'model' => $model,
                ]);

            }

            if ($model->validate() && $model->save()) {
                $model->transaction_type == 10 ? Yii::$app->token->updateUserToken($model->user_id, $model->token) : Yii::$app->token->deductUserToken($model->user_id, $model->token);

                Yii::$app->general->setFlash('create', 'User Token');
                return $this->redirect(['transaction-detail', 'UserTokenTransactionSearch[user_id]' => $user_id]);
            }
        }

        return $this->render('add-token', [
            'model' => $model,
        ]);
    }

    public function actionPaymentDetails()
    {
        $searchModel = new \common\models\UserPaymentTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('payment-details', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
