<?php
namespace common\components;

use Yii;

class Time extends \yii\base\Component
{
    public function convertTimezone($fromTimezone, $toTimezone, $datetime)
    {
        $date = new \DateTime($datetime, new \DateTimeZone($fromTimezone));
        $date->setTimezone(new \DateTimeZone($toTimezone));
        return $date->getTimestamp();
    }
    public function local_to_db($local_date, $user_timezone = "")
    {
        $user_timezone = !empty($user_timezone) ? $user_timezone : \Yii::$app->params['local_timezone'];
        if ($local_date && $user_timezone) {
            return $this->convertTimezone($user_timezone, 'UTC', $local_date);
        } else {
            \Yii::$app->general->throwError(\Yii::t('app', 'Timezone or Date is not specified.'));
        }
    }
    public function format_time($time)
    {
        return !empty($time) ? date('h:i A', $time) : "";
    }
    public function asTime($timestamp)
    {
        \Yii::$app->formatter->timeZone = \Yii::$app->params['timezone'];
        return \Yii::$app->formatter->asTime($timestamp);
    }
    public function asDate($timestamp)
    {
        \Yii::$app->formatter->timeZone = \Yii::$app->params['timezone'];
        return \Yii::$app->formatter->asDate($timestamp);
    }
    public function asDatetime($timestamp)
    {
        \Yii::$app->formatter->timeZone = \Yii::$app->params['timezone'];
        return \Yii::$app->formatter->asDatetime($timestamp);
    }

}