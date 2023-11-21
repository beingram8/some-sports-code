<?php

namespace common\models;

use common\models\SeasonLeague;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonLeagueSearch represents the model behind the search form of `\common\models\SeasonLeague`.
 */
class SeasonLeagueSearch extends SeasonLeague
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'season', 'api_league_id', 'is_active', 'is_main'], 'integer'],
            [['name', 'country', 'api_response'], 'safe'],
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
        $query = SeasonLeague::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'is_active' => $this->is_active,
            'is_main' => $this->is_main,
            'api_league_id' => $this->api_league_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}