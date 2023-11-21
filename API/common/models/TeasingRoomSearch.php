<?php

namespace common\models;

use common\models\TeasingRoom;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeasingRoomSearch represents the model behind the search form of `common\models\TeasingRoom`.
 */
class TeasingRoomSearch extends TeasingRoom
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'likes', 'is_active'], 'integer'],
            [['media', 'user_id', 'thumb_media', 'caption', 'reason_for_disable', 'created_at'], 'safe'],
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
        $query = TeasingRoom::find()->joinWith(['userData']);

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
            'likes' => $this->likes,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'media', $this->media])
            ->andFilterWhere(['OR', ['like', 'first_name', $this->user_id], ['like', 'last_name', $this->user_id]])
            ->andFilterWhere(['like', 'thumb_media', $this->thumb_media])
            ->andFilterWhere(['like', 'caption', $this->caption])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(teasing_room.created_at, "%d %b %Y")', $this->created_at])
            ->andFilterWhere(['like', 'reason_for_disable', $this->reason_for_disable]);

        return $dataProvider;
    }
}
