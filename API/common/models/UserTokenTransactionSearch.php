<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserTokenTransactionSearch extends UserTokenTransaction
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'transaction_type', 'token', 'token_type_id', 'created_at', 'created_by'], 'safe'],
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
        $query = UserTokenTransaction::find()->joinWith(['tokenType']);

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
            'user_id' => $this->user_id,
            'transaction_type' => $this->transaction_type,
        ]);
        $query->andFilterWhere(['like', 'token_type.name', $this->token_type_id])
                ->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%Y-%m-%d")', $this->created_at]);

        return $dataProvider;
    }
}
