<?php

namespace common\components;

use Yii;

class Team extends \yii\base\Component
{
    public function getVoteTeamPoint($match_id, $team_id)
    {
        return \common\models\UserPointTransaction::find()
            ->where(['match_id' => $match_id])
            ->andWhere(['team_id' => $team_id])
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->sum('points');
    }
    public function userTeam($id)
    {
        $team = \common\models\SeasonTeam::find()->where(['id' => $id])->one();
        if (isset($team)) {
            return [
                'id' => $team->id,
                'is_main_team' => $team->is_main_team,
                'is_national_team' => $team->is_national_team,
                'label' => $team->name,
                'logo' => $team->logo,
                'name' => $team->name,
                'value' => $team->name,
            ];
        }
        return [];
    }
    public function getTeam($id)
    {
        $team = \common\models\SeasonTeam::find()->where(['id' => $id])->one();
        if (isset($team)) {
            return $team;
        }
        return '';
    }

    public function user_team($team_id)
    {
        $team = \common\models\SeasonTeam::find()->where(['id' => $team_id])->one();
        if (isset($team)) {
            return $team;
        }
        return '';

    }
    public function getTeamWithSupporters()
    {
        return \common\models\SeasonTeam::find()
            ->select(['season_team.name', 'season_team.logo', 'COUNT(user_data.user_id) as supporter'])
            ->join('LEFT JOIN', 'user_data', 'user_data.team_id = season_team.id')
            ->where(['season_team.is_active' => 1, 'is_main_team' => 1])
            ->groupBy('season_team.id')
            ->orderBy('name ASC')
            ->asArray()->all();

    }
    public function getTeams()
    {
        return \yii\helpers\ArrayHelper::map(\common\models\SeasonTeam::find()
                ->where(['is_active' => 1])->orderBy('name ASC')
                ->asArray()->all(), 'id', 'name');
    }
    public function getTeamsForApiId()
    {
        return \yii\helpers\ArrayHelper::map(\common\models\SeasonTeam::find()->where(['is_active' => 1])->asArray()->all(), 'api_team_id', 'name');
    }
    public function getTeamByApiId($api_team_id)
    {
        return \common\models\SeasonTeam::find()->where(['api_team_id' => $api_team_id])->one();
    }
    public function idFromApiId($api_team_id)
    {
        $team = \common\models\SeasonTeam::find()->where(['api_team_id' => $api_team_id])->one();
        if ($team) {
            return $team->id;
        }
        return \Yii::$app->general->throwError('Please import team first.');
    }

}
