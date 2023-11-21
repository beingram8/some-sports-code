<?php

namespace common\components;

use Yii;

class Token extends \yii\base\Component
{

    public function getTokenValue($name)
    {
        $token_data = \common\models\TokenType::find()->where(['name' => $name])->one();
        if (!empty($token_data)) {
            return $token_data->value;
        } else {
            return 0;
        }
    }

    public function getTokenInfo($plan_id)
    {
        $token_plan_data = \common\models\TokenPlan::find()->where(['id' => $plan_id])->one();
        if (!empty($token_plan_data)) {
            return $token_plan_data;
        } else {
            return '';
        }
    }

    public function getTokenId($name)
    {
        $token_data = \common\models\TokenType::find()->where(['name' => $name])->one();
        if (!empty($token_data)) {
            return $token_data->id;
        } else {
            return '';
        }
    }

    public function quizTokenTransaction($user_id, $is_winner, $total_question, $correct_answer)
    {
        $assign_user_token = new \common\models\UserTokenTransaction();
        $assign_user_token->user_id = $user_id;
        $assign_user_token->transaction_type = 10;
        $assign_user_token->token_type_id = $is_winner ? $this->getTokenId('quiz_winner') : $this->getTokenId('quiz_token'); //quiz token id//
        $assign_user_token->token = $is_winner ? $this->getTokenValue('quiz_winner') : $this->getTokenValue('quiz_token');
        $assign_user_token->created_by = $user_id;
        $assign_user_token->remark = 'For playing the quiz';
        if ($assign_user_token->save()) {
            //Yii::$app->userData->sum_of_token($user_id);
            \Yii::$app->token->updateUserToken($user_id, $assign_user_token->token);
            return [
                'status' => true,
                'data' => [
                    'is_winner' => $is_winner,
                    'total_question' => $total_question,
                    'correct_answer' => $correct_answer,
                    'earn_token' => $assign_user_token->token,
                    'is_animation' => true,
                ],
            ];
        } else {
            return ['status' => false, 'message' => json_encode($assign_user_token->errors)];
        }
    }

    public function surveyTokenTransaction()
    {
        $user_id = \Yii::$app->user->id;
        $assign_user_token = new \common\models\UserTokenTransaction();
        $assign_user_token->user_id = $user_id;
        $assign_user_token->transaction_type = 10;
        $assign_user_token->token_type_id = $this->getTokenId('survey_token'); //survey token id//
        $assign_user_token->token = $this->getTokenValue('survey_token');
        $assign_user_token->created_by = $user_id;
        $assign_user_token->remark = 'For participating in the survey';

        if ($assign_user_token->save()) {
            \Yii::$app->token->updateUserToken($user_id, $assign_user_token->token);
            //Yii::$app->userData->sum_of_token($user_id);
            return [
                'status' => true,
                'data' => [
                    'earn_token' => $assign_user_token->token,
                    'is_animation' => true,
                ],
                'message' => 'Survey finished successfully',
            ];
        } else {
            return ['status' => false, 'message' => json_encode($assign_user_token->errors)];
        }
    }

    public function newsTokenTransaction($news_id, $is_like)
    {
        $user_id = \Yii::$app->user->id;
        $assign_user_token = new \common\models\UserTokenTransaction();
        $assign_user_token->user_id = $user_id;
        $assign_user_token->transaction_type = 10;
        $assign_user_token->token_type_id = $this->getTokenId('like_comment_news');
        $assign_user_token->token = $this->getTokenValue('like_comment_news');
        $assign_user_token->created_by = $user_id;
        $assign_user_token->news_like_id = $is_like == true ? $news_id : '';
        $assign_user_token->news_comment_id = $is_like == false ? $news_id : '';
        $assign_user_token->remark = 'For liking or commenting on news';
        if ($assign_user_token->save()) {
            return true;
        }
        return false;
    }

    public function teasingTokenTransaction($teasing_id, $is_like)
    {
        $user_id = \Yii::$app->user->id;
        $assign_user_token = new \common\models\UserTokenTransaction();
        $assign_user_token->user_id = $user_id;
        $assign_user_token->transaction_type = 10;
        $assign_user_token->token_type_id = $this->getTokenId('teasing_token');
        $assign_user_token->token = $this->getTokenValue('teasing_token');
        $assign_user_token->created_by = $user_id;
        $assign_user_token->teasing_like_id = $is_like == true ? $teasing_id : '';
        $assign_user_token->teasing_comment_id = $is_like == false ? $teasing_id : '';
        $assign_user_token->remark = 'To add, like or comment on a joke post';
        if ($assign_user_token->save()) {
            return true;
        }
        return false;
    }

    public function videoTokenTransaction($video_id)
    {
        $user_id = \Yii::$app->user->id;
        $assign_user_token = new \common\models\UserTokenTransaction();
        $assign_user_token->user_id = $user_id;
        $assign_user_token->transaction_type = 10;
        $assign_user_token->token_type_id = $this->getTokenId('video_watch');
        $assign_user_token->token = $this->getTokenValue('video_watch');
        $assign_user_token->created_by = $user_id;
        $assign_user_token->video_id = $video_id;
        $assign_user_token->remark = 'For watching a video';
        if ($assign_user_token->save()) {
            return true;
        }
        return false;
    }

    public function userTokenTransaction($user_id)
    {
        $assign_user_token = new \common\models\UserTokenTransaction();
        $assign_user_token->user_id = $user_id;
        $assign_user_token->transaction_type = 10;
        $assign_user_token->token_type_id = $this->getTokenId('welcome_token');
        $assign_user_token->token = $this->getTokenValue('welcome_token');
        $assign_user_token->created_by = $user_id;
        $assign_user_token->remark = 'For registering on Fan Rating';
        if ($assign_user_token->save()) {
            return true;
        }
        return false;
    }

    public function deductUserToken($user_id, $token)
    {
        $user = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        if (isset($user)) {
            $user->token = $user->token - $token;
            $user->save(false);
            return true;
        }
        return false;
    }

    public function updateUserToken($user_id, $token)
    {
        $user = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        if (isset($user)) {
            $user->token = $user->token + $token;
            $user->save(false);
            return true;
        }
        return false;
    }

}
