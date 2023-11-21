<?php

namespace common\models;

use common\models\Quiz;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * QuizSearch represents the model behind the search form of `common\models\Quiz`.
 */
class QuizSearch extends Quiz
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'created_at'], 'integer'],
            [['quiz_title', 'quiz_description', 'start_date', 'end_date'], 'safe'],
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
        $query = Quiz::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'quiz_title', $this->quiz_title])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(start_date, "%Y-%m-%d")', $this->start_date])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(end_date, "%Y-%m-%d")', $this->end_date])
            ->andFilterWhere(['like', 'quiz_description', $this->quiz_description]);

        return $dataProvider;
    }
}
