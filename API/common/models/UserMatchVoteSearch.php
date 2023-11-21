<?php

namespace common\models;

use common\models\UserMatchVote;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserMatchVoteSearch represents the model behind the search form of `common\models\UserMatchVote`.
 */
class UserMatchVoteSearch extends UserMatchVote
{
    public $user;
    public $player;
    public $position;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'season', 'team_id', 'player_id','vote'], 'integer'],
            [['match_id', 'user', 'player', 'created_at', 'user_id','position'], 'safe']
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
        $query = UserMatchVote::find();

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
            'team_id' => $this->team_id,
            'player_id' => $this->player_id,
            'vote' => $this->vote,
        ]);

        if ($this->user) {
            $users = \common\models\UserData::find()->where(['OR', ['like', 'first_name', $this->user],
                ['like', 'last_name', $this->user], ['like', 'username', $this->user]])->asArray()->all();
            $user_ids = \yii\helpers\ArrayHelper::getColumn($users, 'user_id');
            $query->andWhere(['IN', 'user_id', $user_ids]);
        } else {
            $query->andFilterWhere(['user_id' => $this->user_id]);
        }
        if ($this->player) {
            $players = \common\models\SeasonTeamPlayer::find()->where(['like', 'name', $this->player])->asArray()->all();
            $player_ids = \yii\helpers\ArrayHelper::getColumn($players, 'id');
            $query->andWhere(['IN', 'player_id', $player_ids]);
        }

        if ($this->position) {
            $positions = \common\models\SeasonMatchPlayer::find()->where(['like', 'position', $this->position])->asArray()->all();
            $player_ids = \yii\helpers\ArrayHelper::getColumn($positions, 'player_id');
            $query->andWhere(['IN', 'player_id', $player_ids]);
        }

        $query->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at]);

        return $dataProvider;
    }
    public function searchVoteUsers($params)
    {
        $query = UserMatchVote::find()->groupBy('user_id');

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
            'team_id' => $this->team_id,
            'player_id' => $this->player_id,
            'vote' => $this->vote,
        ]);

        if ($this->user) {
            $users = \common\models\UserData::find()->where(['OR', ['like', 'first_name', $this->user],
                ['like', 'last_name', $this->user], ['like', 'username', $this->user]])->asArray()->all();
            $user_ids = \yii\helpers\ArrayHelper::getColumn($users, 'user_id');
            $query->andWhere(['IN', 'user_id', $user_ids]);
        } else {
            $query->andFilterWhere(['user_id' => $this->user_id]);
        }
        if ($this->player) {
            $players = \common\models\SeasonTeamPlayer::find()->where(['like', 'name', $this->player])->asArray()->all();
            $player_ids = \yii\helpers\ArrayHelper::getColumn($players, 'id');
            $query->andWhere(['IN', 'player_id', $player_ids]);
        }

        $query->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at]);

        return $dataProvider;
    }
}
