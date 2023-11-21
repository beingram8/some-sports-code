<?php

namespace common\models;

use common\models\SeasonMatch;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonMatchSearch represents the model behind the search form of `\common\models\SeasonMatch`.
 */
class SeasonMatchSearch extends SeasonMatch
{
    public $user_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'season', 'is_point_calculated', 'is_vote_enabled', 'league_id', 'match_timestamp', 'winner_team_id', 'team_home_id', 'team_away_id', 'goal_of_home_team', 'goal_of_away_team', 'is_match_finished', 'vote_closing_at', 'api_match_id'], 'integer'],
            [['match_date', 'match_ground', 'match_city', 'api_response', 'user_id'], 'safe'],
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
        $this->load($params);
        $query = SeasonMatch::find();
        if ($this->is_point_calculated) {
            $query->orderBy('match_timestamp DESC');
        } else if ($this->is_vote_enabled == 1) {
            $query->orderBy('match_timestamp DESC');
        } else if (isset($this->is_vote_enabled) && $this->is_vote_enabled == 0) {
            $query->orderBy('match_timestamp ASC,is_vote_enabled ASC');
        } else {
            $query->orderBy('is_vote_enabled ASC,match_timestamp DESC');
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            // 'pagination' => [
            //     'pageSize' => 4,
            // ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'season' => $this->season,
            'league_id' => $this->league_id,
            'is_vote_enabled' => $this->is_vote_enabled,
            'is_point_calculated' => $this->is_point_calculated,
            'match_timestamp' => $this->match_timestamp,
            'winner_team_id' => $this->winner_team_id,
            'match_date' => $this->match_date,
            'team_home_id' => $this->team_home_id,
            'team_away_id' => $this->team_away_id,
            'goal_of_home_team' => $this->goal_of_home_team,
            'goal_of_away_team' => $this->goal_of_away_team,
            'is_match_finished' => $this->is_match_finished,
            'vote_closing_at' => $this->vote_closing_at,
            'api_match_id' => $this->api_match_id,
        ]);

        $query->andFilterWhere(['like', 'match_ground', $this->match_ground])
            ->andFilterWhere(['like', 'match_city', $this->match_city])
            ->andFilterWhere(['like', 'api_response', $this->api_response]);

        return $dataProvider;
    }

    public function searchMatch($params)
    {
        $this->load($params);
        $sql = '(SELECT `match_id` FROM `user_match_vote` WHERE user_id = ' . $this->user_id . ')';
        $query = SeasonMatch::find()->where('id in ' . $sql . '');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'season' => $this->season,
            'league_id' => $this->league_id,
            'is_vote_enabled' => $this->is_vote_enabled,
            'is_point_calculated' => $this->is_point_calculated,
            'match_timestamp' => $this->match_timestamp,
            'winner_team_id' => $this->winner_team_id,
            'match_date' => $this->match_date,
            'team_home_id' => $this->team_home_id,
            'team_away_id' => $this->team_away_id,
            'goal_of_home_team' => $this->goal_of_home_team,
            'goal_of_away_team' => $this->goal_of_away_team,
            'is_match_finished' => $this->is_match_finished,
            'vote_closing_at' => $this->vote_closing_at,
            'api_match_id' => $this->api_match_id,
        ]);

        $query->andFilterWhere(['like', 'match_ground', $this->match_ground])
            ->andFilterWhere(['like', 'match_city', $this->match_city])
            ->andFilterWhere(['like', 'api_response', $this->api_response]);

        return $dataProvider;
    }
}