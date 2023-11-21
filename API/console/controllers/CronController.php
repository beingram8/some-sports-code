<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 */
class CronController extends Controller
{
    public function behaviors()
    {
        return [
            'cronLogger' => [
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => ['*'],
            ],
        ];
    }
    //########## command : php yii cron/remove-log -  Per day one time
    public function actionRemoveLog()
    {

        $sql = "DELETE FROM `cron_schedule` WHERE dateCreated <= DATE(NOW()) - INTERVAL 4 DAY";
        \Yii::$app->db->createCommand($sql)->execute();

        $sql = "DELETE FROM `log` WHERE `log_time`< UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 15 DAY))";
        \Yii::$app->db->createCommand($sql)->execute();
    }

    //Cron // 10 min // make match finished and voting enable and fetching player
    public function actionFetchSubstitueIfRemain()
    {
        \Yii::$app->match->fetchRemainingSubstitute();
    }

    //Cron // 10 min // make match finished and voting enable and fetching player
    public function actionFinishMatch()
    {
        $start_time = strtotime(date('Y-m-d', time()));
        $end_time = time();
        $matches = \common\models\SeasonMatch::find()->andWhere(['is_vote_enabled' => 0])
            ->where(['>', 'match_timestamp', $start_time]) // match_timestamp > $start_time
            ->andWhere(['<=', 'match_timestamp', $end_time]) // match_timestamp <= $end_time
            ->andWhere(['is_vote_enabled' => 0])
            ->all();
        if ($matches) {
            foreach ($matches as $match) {
                Yii::$app->match->updateFixture($match->api_match_id);
            }
        }
    }

    // 10 min
    public function actionCloseVoting()
    {
        \common\models\SeasonMatch::updateAll(['is_vote_enabled' => 2], ['<', 'vote_closing_at', time()]);
    }

    public function actionPointCalculation()
    {
        //Step-1 Get Matches which has is_match_finished =1 (Finished) and is_vote_enabled = 2(close) and is_point_calculated = 0
        $matches = \common\models\SeasonMatch::find()->where(['is_vote_enabled' => 2, 'is_point_calculated' => 0])->all();
        if ($matches) {
            foreach ($matches as $match) {
                \Yii::$app->match->calcPointForMatch($match);
            }
        } else {
            // No Matches
        }

    }
    // every 5 min
    public function actionSendPushNotification()
    {
        \Yii::$app->notification->sendPush();
    }
    // 5 min
    public function actionManageQuizSurveyStatus()
    {

        $current_time = date('Y-m-d H:i');
        $quiz = \common\models\Quiz::find()->where(
            [
                'AND',
                ['<=', 'start_date', $current_time],
                ['>=', 'end_date', $current_time],
                ['=', 'is_active', 0],
            ],
        )->one();
        if ($quiz) {
            $quiz->is_active = 1;
            if ($quiz->save()) {
                \Yii::$app->notification->savePush(
                    'Now you can play the quiz.',
                    'To earn Fan Coins, play the quiz.',
                    'quiz',
                    ['quiz_id' => $quiz->id, 'type' => 'quiz_online'],
                    \Yii::$app->security->generateRandomString(12),
                    $per_page = 1000
                );
            }

        }
        $current_time = date('Y-m-d H:i');
        \common\models\Quiz::updateAll(['is_active' => 2],
            ['<', 'end_date', $current_time]);

        $survey = \common\models\Survey::find()->where(['AND',
            ['<=', 'start_date', $current_time],
            ['>=', 'end_date', $current_time],
            ['=', 'is_active', 0],
        ])->one();
        if ($survey) {
            $survey->is_active = 1;
            if ($survey->save()) {
                \Yii::$app->notification->savePush(
                    'The survey is online.',
                    'To earn Fan Coins, take the survey.',
                    'survey',
                    ['survey_id' => $survey->id, 'type' => 'survey_online'],
                    \Yii::$app->security->generateRandomString(12),
                    $per_page = 1000
                );
            }

        }

        $current_time = date('Y-m-d H:i');
        \common\models\Survey::updateAll(['is_active' => 2],
            ['<', 'end_date', $current_time]);

    }
}
