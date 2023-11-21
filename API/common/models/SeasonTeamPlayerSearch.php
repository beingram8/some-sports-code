<?php

namespace common\models;

use common\models\SeasonTeamPlayer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonTeamPlayerSearch represents the model behind the search form of `\common\models\SeasonTeamPlayer`.
 */
class SeasonTeamPlayerSearch extends SeasonTeamPlayer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'api_player_id'], 'integer'],
            [['photo', 'name', 'api_response'], 'safe'],
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
        $query = SeasonTeamPlayer::find();

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
            'team_id' => $this->team_id,
            'api_player_id' => $this->api_player_id,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'api_response', $this->api_response]);

        return $dataProvider;
    }
}
