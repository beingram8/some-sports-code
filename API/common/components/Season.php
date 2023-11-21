<?php

namespace common\components;

use Yii;

class Season extends \yii\base\Component
{
    public function getSeasons()
    {
        $season = \common\models\Season::find()->where(['is_expired' => 0])->asArray()->all();
        return \yii\helpers\ArrayHelper::map($season, 'season', 'title');
    }
    public function getSeason($season)
    {
        $season = \common\models\Season::find()->where(['season' => $season])->one();
        return $season;
    }
    public function getCurrentSeason()
    {
        $season = \common\models\Season::find()->where(['is_expired' => 0])->asArray()->one();
        if ($season) {
            return $season['season'];
        } else {
            return date('Y');
        }
    }

}