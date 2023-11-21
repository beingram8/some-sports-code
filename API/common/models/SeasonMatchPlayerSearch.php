<?php

namespace common\models;

use common\models\SeasonMatchPlayer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonTeamPlayerSearch represents the model behind the search form of `\common\models\SeasonTeamPlayer`.
 */
class SeasonMatchPlayerSearch extends SeasonMatchPlayer
{
    public $season;
    public $league;
    public $player;
    public $match_day;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'season', 'match_day'], 'integer'],
            [['player', 'league', 'position'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SeasonMatchPlayer::find()
                ->join('INNER JOIN', 'season_team', 'season_team.id=season_match_players.team_id')
                ->groupBy('season_match_players.player_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['team_id'] = [
            'asc' => ['season_team.name' => SORT_ASC],
            'desc' => ['season_team.name' => SORT_DESC],
        ];

        if ($this->player) {
            $player_ids = \common\models\SeasonTeamPlayer::find()->where(['like', 'name', $this->player])->asArray()->all();
            $player_ids = \yii\helpers\ArrayHelper::getColumn($player_ids, 'id');
            $query->andWhere(['IN', 'player_id', $player_ids]);
        }

        if ($this->match_day) {
            $match_data = \common\models\SeasonMatch::find()->where(['match_day' => $this->match_day])->asArray()->all();
            $match_ids = \yii\helpers\ArrayHelper::getColumn($match_data, 'id');

            $player_data = \common\models\SeasonMatchPlayer::find()->where(['IN', 'match_id', $match_ids])->asArray()->all();
            $player_ids = \yii\helpers\ArrayHelper::getColumn($player_data, 'player_id');
            $query->andWhere(['IN', 'player_id', $player_ids]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'season_match_players.id' => $this->id,
            'team_id' => $this->team_id,
            // 'player_id' => $this->player_id,
            'position' => $this->position,
        ]);

        return $dataProvider;
    }
}
