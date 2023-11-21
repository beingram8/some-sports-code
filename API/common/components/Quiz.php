<?php

namespace common\components;

use Yii;

class Quiz extends \yii\base\Component
{
    public function currentQuiz()
    {
        return \common\models\Quiz::find()->where(['is_active' => 1])->one();
    }
    public function currentQuizId()
    {
        $data = \common\models\Quiz::find()->where(['is_active' => 1])->one();
        if ($data) {
            return $data->id;
        } else {
            \Yii::$app->general->throwError('There are no active quiz.');
        }
    }
}