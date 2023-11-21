<?php

namespace common\models;

use common\models\Survey;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SurveySearch represents the model behind the search form of `common\models\Survey`.
 */
class SurveySearch extends Survey
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            [['sponsored_by', 'description', 'start_date', 'end_date', 'created_at'], 'safe'],
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
        $query = Survey::find();
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
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'sponsored_by', $this->sponsored_by])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(start_date, "%d %b %Y")', $this->start_date])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(end_date, "%d %b %Y")', $this->end_date])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at]);

        return $dataProvider;
    }
}
