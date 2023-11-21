<?php

namespace common\models;

use common\models\SurveyQuestion;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SurveyQuestionSearch represents the model behind the search form of `common\models\Quiz`.
 */
class SurveyQuestionSearch extends SurveyQuestion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['survey_id', 'option_type'], 'integer'],
            [['question'], 'safe'],
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
        $query = SurveyQuestion::find()->where(['survey_id' => $this->survey_id]);

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
            'option_type' => $this->option_type,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question]);

        return $dataProvider;
    }
}
