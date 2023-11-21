<?php

namespace frontend\modules\v1\controllers;

use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class SurveyController extends ActiveController
{
    public $modelClass = '';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'list' => ['get'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['list','survey-result'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['list','survey-result'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionList()
    {
        $surveyData = \Yii::$app->survey->currentSurvey();
        if ($surveyData) {
            $is_result = true;
            $data = \common\models\SurveyQuestion::getQuestionList($surveyData->id);
            return ['status' => true, 'data' => $data];
        } else {
            return ['status' => false, 'message' => \Yii::t('app','Non ci sono sondaggi attivi')];
        }
    }
    public function actionDetails()
    {
        $surveyData = \Yii::$app->survey->currentSurvey();
        if (!empty($surveyData)) {
            $is_already_performed = \common\models\SurveyUserSelectedOption::find()
                ->where(['survey_id' => $surveyData->id])
                ->andWhere(['user_id' => Yii::$app->user->id])->count();
            if ($is_already_performed) {
                return [
                    'status' => true,
                    'data' => [
                        'child_data' => [
                        ],
                        'is_result' => true,
                    ],
                ];
            } else {
                return [
                    'status' => true,
                    'data' => [
                        'child_data' => [
                            'title' => $surveyData->sponsored_by,
                            'sponsor_adv' => $surveyData->sponsor_adv,
                            'description' => $surveyData->description,
                        ],
                        'is_result' => false,
                    ],
                ];
            }
        } else {
            return ['status' => false, 'message' => \Yii::t('app','Nessun dato trovato')];
        }
    }

    public function actionStoreOption()
    {
        $survey_id = \Yii::$app->survey->currentSurveyId();
        $model = new \common\models\SurveyUserSelectedOption();
        $model->survey_id = $survey_id;
        $model->user_id = Yii::$app->user->id;
        $model->load(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $check_user = \common\models\SurveyUserSelectedOption::find()
                ->where(['survey_id' => $survey_id, 'survey_question_id' => $model->survey_question_id])
                ->andWhere(['user_id' => Yii::$app->user->id])->one();
            if (!empty($check_user)) {
                return ['status' => false, 'message' => \Yii::t('app','You already given answer for this question.')];
            }
            if ($model->validate() && $model->save()) {
                return ['status' => true, 'message' => \Yii::t('app','Option added successfully')];
            } else {
                return ['status' => false, 'message' => \Yii::$app->general->error($model->errors)];
            }
        } else {
            return ['status' => false, 'message' => \Yii::$app->general->error($model->errors)];
        }
    }

    public function actionSurveyResult()
    {
        $survey_id = \Yii::$app->survey->currentSurveyId();
        if(!empty($survey_id)){
            return Yii::$app->token->surveyTokenTransaction();
        } else {
            return ['status' => false, 'message' => \Yii::t('app','Non ci sono sondaggi attivi')];
        }
    }
}