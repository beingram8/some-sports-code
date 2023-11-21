<?php

namespace frontend\modules\v1\controllers;

use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class QuizController extends ActiveController
{
    public $modelClass = 'common\models\QuizAnswer';

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
                'question-list' => ['get'],
                'store-answer' => ['post'],
                'quiz-result' => ['get'],
                'quiz-details' => ['get'],

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
            'only' => ['question-list', 'store-answer', 'quiz-result', 'quiz-details'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['question-list', 'store-answer', 'quiz-result', 'quiz-details'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionQuestionList()
    {
        $quizData = \common\models\Quiz::find()->where(['is_active' => 1])->one();
        if ($quizData) {
            $is_result = true;
            $model = new \common\models\QuizQuestion();
            $data = $model->getQuestionList($quizData->id);
            return ['status' => true, 'data' => $data];
        } else {
            return ['status' => false, 'message' => \Yii::t('app','There are no any active Quiz')];
        }
    }

    public function actionQuizDetails()
    {
        $quizData = \Yii::$app->quiz->currentQuiz();
        if (!empty($quizData)) {
            $is_already_performed = \common\models\QuizAnswer::find()
                ->where(['quiz_id' => $quizData->id])
                ->andWhere(['user_id' => Yii::$app->user->id])->count();
            
            $check_user =  \common\models\QuizWinner::find()
                ->where(['quiz_id' => $quizData->id])
                ->andWhere(['winner_user_id' => Yii::$app->user->id])->one();

            if ($is_already_performed || $check_user) {
                $is_winner = \common\models\QuizWinner::find()->where(['quiz_id' => $quizData->id,
                    'winner_user_id' => Yii::$app->user->id, 'is_winner' => 1])
                    ->count();
                $total_question = \common\models\QuizAnswer::find()->where(['quiz_id' => $quizData->id, 'user_id' => Yii::$app->user->id])->count();
                $correct_answer = \common\models\QuizAnswer::find()->where(['quiz_id' => $quizData->id, 'user_id' => Yii::$app->user->id, 'is_correct' => 1])->count();

                return [
                    'status' => true,
                    'data' => [
                        'child_data' => [
                            'is_winner' => $is_winner ? true : false,
                            'total_question' => $total_question,
                            'correct_answer' => $correct_answer,
                            'earn_token' => $is_winner ? \Yii::$app->token->getTokenValue('quiz_winner') :
                            \Yii::$app->token->getTokenValue('quiz_token'),
                        ],
                        'is_result' => true,
                    ],
                ];
            } else {
                return [
                    'status' => true,
                    'data' => [
                        'child_data' => [
                            'title' => $quizData->quiz_title,
                            'description' => $quizData->quiz_description,
                        ],
                        'is_result' => false,
                    ],
                ];
            }
        } else {
            return ['status' => false, 'message' => \Yii::t('app','Nessun dato trovato')];
        }
    }

    public function actionStoreAnswer()
    {
        $model = new \common\models\QuizAnswer();
        $model->quiz_id = \Yii::$app->quiz->currentQuizId();
        $model->user_id = Yii::$app->user->id;
        $model->is_correct = 0;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $check_user = \common\models\QuizAnswer::find()
                ->where(['question_id' => $model->question_id])
                ->andWhere(['user_id' => Yii::$app->user->id])->one();
            if (!empty($check_user)) {
                return ['status' => false, 'message' => \Yii::t('app','You already given answer for this question.')];
            }

            $check_answer = \common\models\QuizQuestion::find()
                ->where(['id' => $model->question_id, 'correct_ans' => $model->selected_option])
                ->one();
            if ($check_answer) {
                $model->is_correct = 1;
            }

            if ($model->save()) {
                if ($model->is_correct) {
                    return ['status' => true, 'data' => ['is_correct' => true]];
                } else {
                    return ['status' => true, 'data' => ['is_correct' => false]];
                }
            } else {
                return ['status' => false, 'message' => \Yii::$app->general->error($model->errors)];
            }

        } else {
            return ['status' => false, 'message' => \Yii::t('app','Parameter are missing.')];
        }
    }

    public function actionQuizResult()
    {
        $quiz_id = \Yii::$app->quiz->currentQuizId();
        $total_question = \common\models\QuizAnswer::find()->where(['quiz_id' => $quiz_id, 'user_id' => Yii::$app->user->id])->count();
        $correct_answer = \common\models\QuizAnswer::find()->where(['quiz_id' => $quiz_id, 'user_id' => Yii::$app->user->id,
            'is_correct' => 1])->count();

        $model = new \common\models\QuizWinner();
        $model->quiz_id = $quiz_id;
        $model->winner_user_id = Yii::$app->user->id;
        $model->is_winner = $total_question == $correct_answer & $correct_answer > 0 ? 1 : 0;
        $model->total_correct_ans = $correct_answer;
        if ($model->save()) {
            return Yii::$app->token->quizTokenTransaction($model->winner_user_id, $model->is_winner, $total_question, $correct_answer);
        } else {
            return ['status' => false, 'message' => \Yii::$app->general->error($model->errors)];
        }
    }

}