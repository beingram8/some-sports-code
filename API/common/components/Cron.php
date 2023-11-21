<?php

namespace common\components;

use Yii;

class Cron extends \yii\base\Component
{
    public function getLastExecutionTime($jobCode)
    {
        $data = \common\models\CronSchedule::find()->where(['jobCode' => $jobCode])
            ->orderBy('id DESC')->one();
        if ($data) {
            return $data->dateCreated;
        } else {
            return '-';
        }
    }

    public function getNoOfExecution($jobCode)
    {
        return \common\models\CronSchedule::find()->where(['jobCode' => $jobCode])
            ->andWhere(['LIKE', 'dateCreated', date('Y-m-d')])
            ->orderBy('id DESC')->count();

    }
}