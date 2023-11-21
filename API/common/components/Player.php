<?php

namespace common\components;

use Yii;

class Player extends \yii\base\Component
{
    public function playerInfo($player_id)
    {
        $player = \common\models\SeasonMatchPlayer::find()->where(['player_id' => $player_id])->one();
        if (!empty($player)) {
            return $player;
        }
        return [];
    }

    public function positionFullForm($position_code = -1)
    {
        $positions = ['D' => 'Defender', 'M' => 'Midfielder', 'G' => 'Goalkeeper', 'F' => 'Forward', 'Coach' => 'Coach'];
        if ($position_code != -1) {
            if (isset($positions[$position_code])) {
                return $positions[$position_code];
            }
            return $position_code;
        }
        return $positions;
    }

    public function idFromApiId($api_team_id)
    {
        $player = \common\models\SeasonTeamPlayer::find()->where(['api_player_id' => $api_team_id])->one();
        if ($player) {
            return $player->id;
        }
        return \Yii::$app->general->throwError('Please import player first.');
    }

    public function teamPlayer($match_id, $team_id)
    {
        $query = \common\models\SeasonMatchPlayer::find()
            ->join('INNER JOIN', 'season_team_player', 'season_team_player.id = season_match_players.player_id')
            ->where(['season_match_players.match_id' => $match_id,
                'season_match_players.team_id' => $team_id,
                'season_match_players.type' => 1])
        // ->orderBy('season_team_player.name ASC')
            ->all();

        $playerEleven = [];
        $tempEleven = [];
        if (isset($query)) {
            foreach ($query as $value) {
                if (isset($value->player)) {
                    $tempEleven = [
                        'player_id' => $value->player->id,
                        'name' => $value->player->name,
                        'photo' => $value->player->photo,
                        'position' => $this->positionFullForm($value->position),
                        'avg_vote' => !empty($this->getAverageRate($value->player->id)) ? $this->getAverageRate($value->player->id) : ''
                    ];
                }
                array_push($playerEleven, $tempEleven);
            }
            return $playerEleven;
        }
        return [];
    }

    public function teamSubstitute($match_id, $team_id)
    {
        $query = \common\models\SeasonMatchPlayer::find()
            ->join('INNER JOIN', 'season_team_player', 'season_team_player.id = season_match_players.player_id')
            ->where(['season_match_players.match_id' => $match_id,
                'season_match_players.team_id' => $team_id,
                'season_match_players.type' => 2])
        // ->orderBy('season_team_player.name ASC')
            ->all();

        $subEleven = [];
        $tempEleven = [];
        if (isset($query)) {
            foreach ($query as $value) {
                if (isset($value->player)) {
                    $tempEleven = [
                        'player_id' => $value->player->id,
                        'name' => $value->player->name,
                        'photo' => $value->player->photo,
                        'position' => $this->positionFullForm($value->position),
                        'avg_vote' => !empty($this->getAverageRate($value->player->id)) ? $this->getAverageRate($value->player->id) : ''
                    ];
                }
                array_push($subEleven, $tempEleven);
            }
            return $subEleven;
        }
        return [];
    }

    public function teamCoach($match_id, $team_id)
    {
        $query = \common\models\SeasonMatchPlayer::find()
            ->join('INNER JOIN', 'season_team_player', 'season_team_player.id = season_match_players.player_id')
            ->where(['season_match_players.match_id' => $match_id,
                'season_match_players.team_id' => $team_id,
                'season_match_players.type' => 3])
        // ->orderBy('season_team_player.name ASC')
            ->all();

        $coach = [];
        $temp = [];
        if (isset($query)) {
            foreach ($query as $value) {
                if (isset($value->player)) {
                    $temp = [
                        'player_id' => $value->player->id,
                        'name' => $value->player->name,
                        'photo' => $value->player->photo,
                        'position' => $this->positionFullForm($value->position),
                        'avg_vote' => !empty($this->getAverageRate($value->player->id)) ? $this->getAverageRate($value->player->id) : ''
                    ];
                }
                array_push($coach, $temp);
            }
            return $coach;
        }
        return [];
    }

    public function getAveragePoint($player_id)
    {
        $player_transaction = \common\models\UserPointTransactionSearch::find()->where(['player_id' => $player_id])->average('points');
        if (!empty($player_transaction)) {
            return $player_transaction;
        } else {
            return 0;
        }
    }

    public function getAverageRate($player_id)
    {
        $current_season = Yii::$app->season->getCurrentSeason();
        $avg_rate = \common\models\UserMatchVote::find()->where(['player_id' => $player_id])
            ->average('vote');
        if (!empty($avg_rate)) {
            return number_format($avg_rate, 2);
        } else {
            return 0;
        }
    }

    public function getAverageRateByMatch($player_id, $match_id)
    {
        $current_season = Yii::$app->season->getCurrentSeason();
        $avg_rate = \common\models\UserMatchVote::find()
            ->where(['season' => Yii::$app->season->getCurrentSeason(), 'player_id' => $player_id])
            ->andWhere(['match_id' => $match_id])
            ->average('vote');
        if (!empty($avg_rate)) {
            return number_format($avg_rate, 2);
        } else {
            return 0;
        }
    }
    public function getAverageRateByMatchDay($match_day, $season, $league_id, $player_id)
    {
        $match_ids = yii\helpers\ArrayHelper::getColumn(\common\models\SeasonMatch::find()
                ->where(['match_day' => $match_day, 'season' => $season, 'league_id' => $league_id])->asArray()->all(), 'id');
        $avg_rate = \common\models\UserMatchVote::find()
            ->select(['AVG(vote) as vote'])
            ->where(['IN', 'match_id', $match_ids])
            ->andWhere(['player_id' => $player_id])
            ->groupBy('match_id')
            ->asArray()->all();
        // print_r($match_ids);die;
        if (!empty($avg_rate)) {
            $array = \yii\helpers\ArrayHelper::getColumn($avg_rate, 'vote');
            return array_sum($array) / count($array);
        } else {
            return 0;
        }
    }
    public function getAverageRateByLeague($league_id, $player_id)
    {
        $match_ids = yii\helpers\ArrayHelper::getColumn(\common\models\SeasonMatch::find()->where(['league_id' => $league_id])->asArray()->all(), 'id');
        $avg_rate = \common\models\UserMatchVote::find()
            ->select(['AVG(vote) as vote'])
            ->where(['IN', 'match_id', $match_ids])
            ->andWhere(['player_id' => $player_id])
            ->groupBy('match_id')
            ->asArray()->all();
        if (!empty($avg_rate)) {
            $array = \yii\helpers\ArrayHelper::getColumn($avg_rate, 'vote');
            return array_sum($array) / count($array);
        } else {
            return 0;
        }
    }

    public function playerListForMatch($match_id)
    {
        $data = [];
        $query = \common\models\SeasonMatchPlayer::find()
            ->where(['match_id' => $match_id])
            ->all();
        if (isset($query)) {
            foreach ($query as $value) {
                if (isset($value->player)) {
                    $data[$value->player->id] = $value->player->name;
                }
            }
        }

        return $data;
    }

}
