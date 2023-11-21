<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "teasing_room".
 *
 * @property int $id
 * @property int $user_id
 * @property string $media
 * @property string $thumb_media
 * @property string|null $caption
 * @property int $likes
 * @property int $is_active
 * @property string|null $reason_for_disable
 * @property int|null $created_at
 *
 * @property User $user
 * @property TeasingRoomComments[] $teasingRoomComments
 * @property TeasingRoomLikes[] $teasingRoomLikes
 * @property TeasingRoomReported[] $teasingRoomReporteds
 */
class TeasingRoom extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    public $my_post;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teasing_room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'media'], 'required'],
            [['user_id', 'likes', 'is_active', 'created_at'], 'integer'],
            [['caption', 'reason_for_disable'], 'string'],
            ['media', 'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Limit is 10MB'],
            //['thumb_media', 'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Limit is 10MB'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['page', 'per_page', 'token', 'my_post', 'is_video'], 'safe'],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'media' => 'Media',
            'thumb_media' => 'Thumb Media',
            'caption' => 'Caption',
            'likes' => 'Likes',
            'is_active' => 'Is Active',
            'reason_for_disable' => 'Reason For Disable',
            'created_at' => 'Created At',
        ];
    }

    public function getList()
    {
        $query = TeasingRoom::find()->where(['is_active' => 1])->orderBy('id DESC');
        if ($this->my_post) {
            $query->andWhere(['user_id' => \Yii::$app->user->id]);
        }
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
            $data[$key]['id'] = $value['id'];
            $data[$key]['username'] = \Yii::$app->userData->username($value['user_id']);
            $data[$key]['user_photo'] = \Yii::$app->userData->photo($value['user_id']);
            $data[$key]['post_thumbnail'] = $value['thumb_media'];
            $data[$key]['post_media'] = $value['media'];
            $data[$key]['caption'] = $value['caption'];
            $data[$key]['is_like'] = \common\models\TeasingRoomLike::find()->where(['teasing_id' => $value['id']])->andWhere(['user_id' => \Yii::$app->user->identity->id])->count();
            $data[$key]['total_likes'] = \common\models\TeasingRoomLike::find()->where(['teasing_id' => $value['id']])->count();
            $data[$key]['total_comments'] = \common\models\TeasingRoomComment::find()->where(['teasing_id' => $value['id']])->count();
            $data[$key]['created_at'] = \Yii::$app->general->timeAgo($value['created_at']);
            $data[$key]['token'] = $value['token'];
            $data[$key]['is_video'] = $value['is_video'];
        }

        return ['rows' => $data, 'pagination' => $pagination];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserData()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[TeasingRoomComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRoomComments()
    {
        return $this->hasMany(TeasingRoomComments::className(), ['teasing_id' => 'id']);
    }

    /**
     * Gets query for [[TeasingRoomLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRoomLikes()
    {
        return $this->hasMany(TeasingRoomLikes::className(), ['teasing_id' => 'id']);
    }

    /**
     * Gets query for [[TeasingRoomReporteds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRoomReporteds()
    {
        return $this->hasMany(TeasingRoomReported::className(), ['teasing_id' => 'id']);
    }
}
