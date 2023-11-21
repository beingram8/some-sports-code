<?php

namespace common\components;

use Yii;

class Survey extends \yii\base\Component
{
    public function currentSurvey()
    {
        return \common\models\Survey::find()->where(['is_active' => 1])->one();
    }
    public function currentSurveyId()
    {
        $data = \common\models\Survey::find()->where(['is_active' => 1])->one();
        if ($data) {
            return $data->id;
        } else {
            \Yii::$app->general->throwError('There are no active Survey.');
        }
    }
}