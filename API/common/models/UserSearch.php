<?php

namespace common\models;

use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $first_name;
    public $username;
    public $gender;
    public $birthdate;
    public $team_id;
    public $token;
    public $point;
    public $level_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level_id', 'is_social', 'status', 'access_token_expired_at'], 'integer'],
            [['email', 'password_hash', 'team_id', 'token', 'point', 'password_reset_token', 'verification_token', 'access_token', 'auth_key', 'created_at', 'first_name', 'username', 'gender', 'birthdate'], 'safe'],
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
        $query = User::find()->joinWith(['authAssignment', 'userData'])->where(['item_name' => 'user']);

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

        $dataProvider->sort->attributes['username'] = [
            'asc' => ['username' => SORT_ASC],
            'desc' => ['username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['first_name'] = [
            'asc' => ['first_name' => SORT_ASC],
            'desc' => ['first_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['team_id'] = [
            'asc' => ['team_id' => SORT_ASC],
            'desc' => ['team_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['level_id'] = [
            'asc' => ['level_id' => SORT_ASC],
            'desc' => ['level_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['token'] = [
            'asc' => ['token' => SORT_ASC],
            'desc' => ['token' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['point'] = [
            'asc' => ['point' => SORT_ASC],
            'desc' => ['point' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['birthdate'] = [
            'asc' => ['birth_date' => SORT_ASC],
            'desc' => ['birth_date' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_social' => $this->is_social,
            'status' => $this->status,
            'team_id' => $this->team_id,
            'level_id' => $this->level_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['OR', ['like', 'first_name', $this->first_name], ['like', 'last_name', $this->first_name], ['like', 'email', $this->first_name]])
            ->orFilterWhere(['like', "CONCAT(first_name, ' ', last_name)", $this->first_name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'point', $this->point])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(user.created_at, "%d %b %Y")', $this->created_at]);

        if (!empty($params['UserSearch']['birthdate'])) {
            $date = date_create($this->birthdate);
            $birthdate = date_format($date, "Y-m-d");
            $query->andFilterWhere(['like', 'birth_date', $birthdate]);
        }

        return $dataProvider;
    }
}
