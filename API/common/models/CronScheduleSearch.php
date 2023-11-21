<?php

namespace common\models;

use common\models\CronSchedule;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CronScheduleSearch represents the model behind the search form of `\common\models\CronSchedule`.
 */
class CronScheduleSearch extends CronSchedule
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['jobCode', 'messages', 'dateCreated', 'dateFinished'], 'safe'],
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
        $query = CronSchedule::find()->orderBy('id DESC');

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
            'status' => $this->status,
            'dateCreated' => $this->dateCreated,
            'dateFinished' => $this->dateFinished,
        ]);

        $query->andFilterWhere(['like', 'jobCode', $this->jobCode])
            ->andFilterWhere(['like', 'messages', $this->messages]);

        return $dataProvider;
    }
}