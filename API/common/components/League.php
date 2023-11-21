<?php

namespace common\components;

use Yii;

class League extends \yii\base\Component
{
    public function getLeagueByApiId($api_league_id)
    {
        return \common\models\SeasonLeague::find()->where(['api_league_id' => $api_league_id])->one();
    }
    public function getLeagues($api_id_base = false)
    {
        $leagues = \common\models\SeasonLeague::find()->where(['is_active' => 1])->asArray()->all();
        $attribute = $api_id_base ? 'api_league_id' : 'id';
        return \yii\helpers\ArrayHelper::map($leagues, $attribute, 'name');
    }
    public function getLeagueById($league_id)
    {
        $league = \common\models\SeasonLeague::find()->where(['id' => $league_id])->one();
        if (isset($league)) {
            return [
                'id' => $league->id,
                'label' => $league->name." (".$league->country.")",
                'logo' => $league->logo,
                'name' => $league->name,
                "country" => $league->country,
                'value' => $league->id,
            ];
        }
        return [];
    }
}