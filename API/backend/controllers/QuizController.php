<?php

namespace backend\controllers;

use common\models\Quiz;
use common\models\QuizSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * QuizController implements the CRUD actions for Quiz model.
 */
class QuizController extends Controller
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
                        'actions' => ['create', 'index', 'update', 'delete', 'index', 'view', 'add-question', 'remove-question', 'update-active'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Quiz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quiz model.
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
     * Creates a new Quiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Quiz();
        if ($model->load(Yii::$app->request->post()) ) {

            if($model->validate() && $model->save()){
                Yii::$app->general->setFlash('create', 'Quiz');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Quiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if($model->validate() && $model->save()){
                Yii::$app->general->setFlash('update', 'Quiz');
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Quiz model.
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

    public function actionAddQuestion($quiz_id, $id = 0)
    {
        $quiz_question = \common\models\QuizQuestion::find()->where(['quiz_id' => $quiz_id, 'id' => $id])->one();
        $model = !empty($quiz_question) ? $quiz_question : new \common\models\QuizQuestion();

        if ($model->load(Yii::$app->request->post())) {

            $d = $model->correct_ans;
            $model->correct_ans = $model->$d;
            $model->quiz_id = $quiz_id;

            if ($model->validate() && $model->save()) {
                if (!empty($quiz_question)) {
                    Yii::$app->general->setFlash('update', 'Question');
                } else {
                    Yii::$app->general->setFlash('create', 'Question');
                }
                return $this->redirect(['add-question', 'quiz_id' => $quiz_id]);
            }
        }

        $searchModel = new \common\models\QuizQuestionSearch();
        $searchModel->quiz_id = $quiz_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('add-question', [
            'model' => $model,
            'quiz_id' => $quiz_id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRemoveQuestion($id, $quiz_id)
    {
        $model = \common\models\QuizQuestion::find()->where(['quiz_id' => $quiz_id, 'id' => $id])->one();

        $model->delete();
        Yii::$app->general->setFlash('delete', 'Question');
        return $this->redirect(['add-question', 'quiz_id' => $quiz_id]);

    }

    public function actionUpdateActive()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            Quiz::updateAll(['is_active' => $data['option']], ['id' => $data['quiz_id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    /**
     * Finds the Quiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quiz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}