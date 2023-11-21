<?php

namespace common\models;

use common\models\TeasingRoom;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeasingRoomSearch represents the model behind the search form of `common\models\TeasingRoom`.
 */
class TeasingReportSearch extends TeasingRoomReported
{
    public $caption;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','teasing_id'], 'integer'],
            [['media', 'reported_user_id', 'caption', 'reason', 'created_at'], 'safe'],
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
        $query = TeasingRoomReported::find()->joinWith(['teasing', 'reportedUser']);

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
            'teasing_id' => $this->teasing_id,
        ]);

        $query->andFilterWhere(['OR', ['like', 'first_name', $this->reported_user_id], ['like', 'last_name', $this->reported_user_id]])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(teasing_room.created_at, "%d %b %Y")', $this->created_at])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'caption', $this->caption]);

        return $dataProvider;
    }
}
