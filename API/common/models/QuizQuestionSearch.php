<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuizQuestion;

/**
 * QuizQuestionSearch represents the model behind the search form of `common\models\Quiz`.
 */
class QuizQuestionSearch extends QuizQuestion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id'], 'integer'],
            [['option_1', 'option_2','option_3', 'option_4'], 'safe'],
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
        $query = QuizQuestion::find()->where(['quiz_id' => $this->quiz_id]);

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
            'quiz_id' => $this->quiz_id,
        ]);

        $query->andFilterWhere(['like', 'option_1', $this->option_1])
            ->andFilterWhere(['like', 'option_2', $this->option_2])
            ->andFilterWhere(['like', 'option_3', $this->option_3])
            ->andFilterWhere(['like', 'option_4', $this->option_4]);

        return $dataProvider;
    }
}
