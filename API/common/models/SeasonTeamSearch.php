<?php

namespace common\models;

use common\models\SeasonTeam;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonTeamSearch represents the model behind the search form of `\common\models\SeasonTeam`.
 */
class SeasonTeamSearch extends SeasonTeam
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'season', 'is_active', 'is_main_team', 'is_national_team', 'api_team_id', 'created_at'], 'integer'],
            [['name', 'logo', 'api_response'], 'safe'],
            [['price_for_super_fab_package'], 'number'],
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
        $query = SeasonTeam::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
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
            'price_for_super_fab_package' => $this->price_for_super_fab_package,
            'is_main_team' => $this->is_main_team,
            'is_national_team' => $this->is_national_team,
            'api_team_id' => $this->api_team_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'api_response', $this->api_response]);

        return $dataProvider;
    }
}