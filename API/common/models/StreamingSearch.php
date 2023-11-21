<?php

namespace common\models;

use common\models\Streaming;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StreamingSearch represents the model behind the search form of `common\models\Streaming`.
 */
class StreamingSearch extends Streaming
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_live', 'created_at'], 'integer'],
            [['title', 'thumb_img'], 'safe'],
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
        $query = Streaming::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_live' => $this->is_live,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'thumb_img', $this->thumb_img])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(created_at, "%d %b %Y")', $this->created_at]);

        return $dataProvider;
    }
}
