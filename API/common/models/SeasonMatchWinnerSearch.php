<?php

namespace common\models;

use common\models\SeasonMatchWinner;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SeasonMatchWinnerSearch represents the model behind the search form of `common\models\SeasonMatchWinner`.
 */
class SeasonMatchWinnerSearch extends SeasonMatchWinner
{
    public $team;
    public $user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'season', 'match_id', 'rank', 'team_id'], 'integer'],
            [['points'], 'number'],
            [['team', 'user','created_at'], 'safe'],
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
        $query = SeasonMatchWinner::find();

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
            'match_id' => $this->match_id,
            'points' => $this->points,
            'rank' => $this->rank,
            'team_id' => $this->team_id,
        ]);
        if ($this->user) {
            $users = \common\models\UserData::find()->where(['OR', ['like', 'first_name', $this->user],
                ['like', 'last_name', $this->user], ['like', 'username', $this->user]])->asArray()->all();

            $user_ids = \yii\helpers\ArrayHelper::getColumn($users, 'user_id');

            $query->andWhere(['IN', 'user_id', $user_ids]);
            
        }
        $query->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at]);

        return $dataProvider;
    }
}