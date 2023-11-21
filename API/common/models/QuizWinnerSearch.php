<?php

namespace common\models;

use common\models\QuizWinner;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * QuizWinnerSearch represents the model behind the search form of `common\models\QuizWinner`.
 */
class QuizWinnerSearch extends QuizWinner
{
    public $user;
    public $quiz;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quiz_id'], 'integer'],
            [['user', 'quiz'], 'safe'],
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
        $query = QuizWinner::find();

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

        if ($this->user) {
            $users = \common\models\UserData::find()->where(['OR', ['like', 'first_name', $this->user],
                ['like', 'last_name', $this->user], ['like', 'username', $this->user]])->asArray()->all();
            $user_ids = \yii\helpers\ArrayHelper::getColumn($users, 'user_id');
            $query->andWhere(['IN', 'winner_user_id', $user_ids]);
        }
        if ($this->quiz) {
            $quiz_name = \common\models\Quiz::find()->where(['like', 'quiz_title', $this->quiz])->asArray()->all();
            $quiz_ids = \yii\helpers\ArrayHelper::getColumn($quiz_name, 'id');
            $query->andWhere(['IN', 'quiz_id', $quiz_ids]);
        }

        return $dataProvider;
    }
}