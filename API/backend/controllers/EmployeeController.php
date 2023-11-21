<?php

namespace backend\controllers;

use common\models\Employee;
use common\models\EmployeeSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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
    private function assignDepartment($department_ids, $user_id)
    {
        if (!empty($department_ids)) {
            $department_ids = array_filter($department_ids);
            \common\models\AttentionUser::deleteAll(['user_id' => $user_id]);
            if (!empty($department_ids)) {
                foreach ($department_ids as $department_id => $attention_role_id) {
                    $model = new \common\models\AttentionUser;
                    $model->user_id = $user_id;
                    $model->department_id = $department_id;
                    $model->attention_role_id = $attention_role_id;
                    if (!$model->save()) {
                        throw new NotFoundHttpException(json_encode($model->errors));
                    }
                }
            }
        }
    }
    //if user type associate User
    private function assignAssociatedDepartment($associated_department_ids, $user_id)
    {
        if (!empty($associated_department_ids)) {
            $associated_department_ids = array_filter($associated_department_ids);
            \common\models\UserAssocitedDepartment::deleteAll(['user_id' => $user_id]);
            if (!empty($associated_department_ids)) {
                foreach ($associated_department_ids as $department_id) {
                    $model = new \common\models\UserAssocitedDepartment;
                    $model->user_id = $user_id;
                    $model->department_id = $department_id;
                    if (!$model->save()) {
                        throw new NotFoundHttpException(json_encode($model->errors));
                    }
                }
            }
        }
    }
    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post())) {
            if (\yii\web\UploadedFile::getInstance($model, 'profile_pic')) {
                $model->profile_pic = Yii::$app->general->upload(\yii\web\UploadedFile::getInstance($model, 'profile_pic'), 500, "users");
            }
            if ($model->save() && $model->roleAssignment($model->id, $model->role)) {

                $user = \common\models\Employee::findOne($model->id);
                $user->setPassword($model->password_hash);
                $user->generateAuthKey();
                $user->generateEmailVerificationToken();
                if ($user->save()) {
                    // $this->assignDepartment($model->department_ids, $model->id);
                    $this->assignAssociatedDepartment($model->associated_department_ids, $model->id);
                    return $this->redirect(['index']);
                } else {
                    $errors = $user->errors;
                    $user->delete();
                    throw new NotFoundHttpException(json_encode($errors));
                }
            } else {
                \Yii::$app->general->throwError(json_encode($model->errors));
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_photo = $model->profile_pic;
        if ($model->load(Yii::$app->request->post())) {

            if (\yii\web\UploadedFile::getInstance($model, 'profile_pic')) {
                $model->profile_pic = Yii::$app->general->upload(\yii\web\UploadedFile::getInstance($model, 'profile_pic'), 500, "users");
            } else {
                $model->profile_pic = $old_photo;
            }
            if ($model->save() && $model->roleAssignment($model->id, $model->role)) {
                //$this->assignDepartment($model->department_ids, $model->id);
                $this->assignAssociatedDepartment($model->associated_department_ids, $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
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
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::find()->where(['users.id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}