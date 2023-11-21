<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "teasing_room_comments".
 *
 * @property int $id
 * @property int $teasing_id
 * @property int $user_id
 * @property string $comment
 * @property int|null $created_at
 *
 * @property TeasingRoom $teasing
 * @property UserData $user
 */
class TeasingRoomComment extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teasing_room_comments';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teasing_id', 'user_id', 'comment'], 'required'],
            [['teasing_id', 'user_id', 'created_at'], 'integer'],
            [['comment'], 'string'],
            [['teasing_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeasingRoom::className(), 'targetAttribute' => ['teasing_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['page', 'per_page'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teasing_id' => 'Teasing ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Teasing]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasing()
    {
        return $this->hasOne(TeasingRoom::className(), ['id' => 'teasing_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'user_id']);
    }

    public function getList($id)
    {
        $teasingPost = \common\models\TeasingRoom::find()->where(['id' => $id])->andWhere(['is_active' => 1])->one();

        if (!empty($teasingPost)) {
            $teasingPost = [
                'id' => $teasingPost->id,
                'caption' => $teasingPost->caption,
                'user_image' => \Yii::$app->userData->photo($teasingPost->user_id),
                'username' => \Yii::$app->userData->username($teasingPost->user_id),
                'created_at' => \Yii::$app->general->timeAgo($teasingPost->created_at),
            ];
        }
        $query = TeasingRoomComment::find()->where(['teasing_id' => $id])->orderBy('id DESC');

        $page = $this->page > 0 ? ($this->page - 1) : 0;
        $pageSize = (int) $this->per_page;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => true,
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
            ],
        ]);

        $models = $provider->getModels();

        $pagination = array_intersect_key(
            (array) $provider->pagination,
            array_flip(
                $paginationParams = [
                    'pageParam',
                    'pageSizeParam',
                    'params',
                    'totalCount',
                    'defaultPageSize',
                    'pageSizeLimit',
                ]
            )
        );

        $totalPage = $pagination['totalCount'] / $pageSize;
        $pagination['totalPage'] = $totalPage;
        $pagination['currentPage'] = $this->page;
        $pagination['isMore'] = $totalPage <= $this->page ? false : true;
        $data = [];

        foreach ($models as $key => $value) {
            $team = \Yii::$app->team->getTeam($value['user']['team_id']);
            $data[$key]['id'] = $value['id'];
            $data[$key]['username'] = \Yii::$app->userData->username($value['user_id']);
            $data[$key]['user_photo'] = \Yii::$app->userData->photo($value['user_id']);
            $data[$key]['team_photo'] = isset($team->logo) ? $team->logo : '-';
            $data[$key]['comment'] = $value['comment'];
            $data[$key]['created_at'] = \Yii::$app->general->timeAgo($value['created_at']);
        }

        return ['post_detail' => $teasingPost, 'rows' => $data, 'pagination' => $pagination];
    }
}
