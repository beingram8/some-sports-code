<?php

namespace common\models;

use common\models\Season;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonSearch represents the model behind the search form of `\common\models\Season`.
 */
class SeasonSearch extends Season
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season', 'is_expired'], 'integer'],
            [['title', 'start_date', 'end_date'], 'safe'],
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
        $query = Season::find();

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
            'season' => $this->season,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_expired' => $this->is_expired,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}