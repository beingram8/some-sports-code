<?php

namespace common\models;

use common\models\UserPointTransaction;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserPointTransactionSearch represents the model behind the search form of `common\models\UserPointTransaction`.
 */
class UserPointTransactionSearch extends UserPointTransaction
{
    public $user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'transaction_type', 'match_id', 'team_id', 'player_id', 'created_at'], 'integer'],
            [['remark', 'user'], 'safe'],
            [['points'], 'number'],
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
        $query = UserPointTransaction::find()->joinWith(['user']);

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
            'user_point_transaction.user_id' => $this->user_id,
            'type' => $this->type,
            'transaction_type' => $this->transaction_type,
            'points' => $this->points,
            'match_id' => $this->match_id,
            'user_point_transaction.team_id' => $this->team_id,
            'player_id' => $this->player_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['OR', ['like', 'user_data.first_name', $this->user], ['like', 'user_data.last_name', $this->user]]);

        return $dataProvider;
    }
}
