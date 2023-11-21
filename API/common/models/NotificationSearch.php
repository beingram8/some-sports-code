<?php

namespace common\models;

use common\models\Notification;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NotificationSearch represents the model behind the search form of `\common\models\Notification`.
 */
class NotificationSearch extends Notification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'is_read', 'created_at', 'push_completed', 'badge_count'], 'integer'],
            [['uuid', 'title', 'message', 'type', 'data', 'group_key', 'push_request', 'push_response'], 'safe'],
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
        $query = Notification::find()->joinWith(['userData']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_read' => $this->is_read,
            'push_completed' => $this->push_completed,
            'badge_count' => $this->badge_count,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['or', ['like', 'user_data.first_name', $this->user_id], ['like', 'user_data.last_name', $this->user_id]])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'group_key', $this->group_key])
            ->andFilterWhere(['like', 'push_request', $this->push_request])
            ->andFilterWhere(['like', 'push_response', $this->push_response]);

        return $dataProvider;
    }
}
