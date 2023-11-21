<?php

namespace backend\controllers;

use common\models\Survey;
use common\models\SurveyQuestion;
use common\models\SurveyQuestionOption;
use common\models\SurveyQuestionSearch;
use common\models\SurveySearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * SurveyController implements the CRUD actions for Survey model.
 */
class SurveyController extends Controller
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
                        'actions' => ['create', 'index', 'update', 'delete', 'index', 'view', 'add-question', 'delete-question', 'update-active', 'survey-result'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Survey models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SurveySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Survey model.
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
     * Creates a new Survey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Survey();

        if ($model->load(Yii::$app->request->post())) {
            
            if ($model->validate() && $model->save()) {
                Yii::$app->general->setFlash('create', 'Survey');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Survey model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            if ($model->validate() && $model->save()) {
                Yii::$app->general->setFlash('update', 'Survey');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Survey model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->img->unlink($model->sponsor_adv);

        $model->delete();
        Yii::$app->general->setFlash('delete', 'Survey');
        return $this->redirect(['index']);
    }

    public function actionAddQuestion($survey_id, $id = 0)
    {
        $model = $id == 0 ? new SurveyQuestion() : $this->findQuestionModel($id);

        $searchModel = new SurveyQuestionSearch;
        $searchModel->survey_id = $survey_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model->survey_id = $survey_id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                $this->saveOption($survey_id, $model->id, $model);
                return $this->redirect(['add-question', 'survey_id' => $survey_id]);
            }
        }
        return $this->render('add-question', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteQuestion($id)
    {
        $model = $this->findQuestionModel($id);
        if ($model->option_type == 1) {
            foreach ($model->surveyQuestionOptions as $data) {
                Yii::$app->img->unlink($data->option_as_img);
            }
        }
        $model->delete();
        Yii::$app->general->setFlash('delete', 'Survey');
        return $this->redirect(['index']);
    }

    public function saveOption($survey_id, $question_id, $model)
    {
        
        $options = array($model->option_text_1, $model->option_text_2, $model->option_text_3, $model->option_text_4);
        SurveyQuestionOption::deleteAll(['survey_id' => $survey_id, 'survey_question_id' => $question_id]);
        foreach ($options as $option) {
            $optionModel = new SurveyQuestionOption;
            $optionModel->survey_id = $survey_id;
            $optionModel->survey_question_id = $question_id;
            $optionModel->option_as_text = $option;
            $optionModel->save();
        }
        return true;
    }

    public function actionSurveyResult($id)
    {
        $query = SurveyQuestion::find()->where(['survey_question.survey_id' => $id])->all();
        $survey_data = \common\models\Survey::findOne($id);
        $sponsored_by = $survey_data->sponsored_by;

        $provider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('survey-result', ['dataProvider' => $provider, 'sponsored_by' => $sponsored_by]);
    }

    public function actionUpdateActive()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            Survey::updateAll(['is_active' => $data['option']], ['id' => $data['survey_id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    /**
     * Finds the Survey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Survey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Survey::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findQuestionModel($id)
    {
        if (($model = SurveyQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
