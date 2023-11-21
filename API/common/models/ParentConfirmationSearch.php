<?php

namespace common\models;

use common\models\ParentConfirmation;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class ParentConfirmationSearch extends ParentConfirmation
{
    public $user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','is_confirm'], 'integer'],
            [['user','created_at'], 'safe'],
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
        $query = ParentConfirmation::find()->where(['is_confirm' => 0]);

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

        if ($this->user) {
            $users = \common\models\UserData::find()->where(['OR', ['like', 'first_name', $this->user],
                ['like', 'last_name', $this->user], ['like', 'username', $this->user]])->asArray()->all();
            $user_ids = \yii\helpers\ArrayHelper::getColumn($users, 'user_id');
            $query->andWhere(['IN', 'user_id', $user_ids]);
        } else {
            $query->andFilterWhere(['user_id' => $this->user_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_confirm' => $this->is_confirm,
        ]);

        $query->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at]);

        return $dataProvider;
    }
}
