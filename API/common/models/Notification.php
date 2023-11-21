<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $user_id
 * @property string $uuid
 * @property string $title
 * @property string|null $message
 * @property string $type
 * @property string|null $is_read
 * @property int $badge_count
 * @property int|null $from_user_id
 * @property int $created_at
 * @property string|null $push_request
 * @property string|null $push_response
 *
 * @property User $fromUser
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    public $notify_type;
    public $user_ids;
    public $team_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
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
            [['title', 'message'], 'required'],
            [['user_id', 'badge_count', 'created_at'], 'integer'],
            [['uuid', 'message', 'type', 'is_read', 'push_request', 'push_response'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['page', 'notify_type', 'team_id', 'user_ids'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'uuid' => 'Uuid',
            'title' => 'Title',
            'message' => 'Message',
            'type' => 'Type',
            'is_read' => 'Is Read',
            'badge_count' => 'Badge Count',
            'from_user_id' => 'From User ID',
            'created_at' => 'Created At',
            'push_request' => 'Push Request',
            'push_response' => 'Push Response',
            'notify_type' => 'Notification Type',
        ];
    }

    public function getItem()
    {
        $query = Notification::find()->where(['user_id' => Yii::$app->user->identity->id]);
        $query->orderBy('id DESC')->asArray();

        $page = $this->page > 0 ? ($this->page - 1) : 0;
        $pageSize = (int) $this->per_page;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => true,
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
                'pageSizeLimit' => [10, 50, 100],
                'pageSizeParam' => 'per_page',
                'validatePage' => true,
                'params' => '',
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

        $pagination['totalPage'] = $pagination['totalCount'] / $pageSize;
        $pagination['currentPage'] = $this->page;
        $pagination['isMore'] = $pagination['totalPage'] <= $this->page ? false : true;

        $data = [];
        foreach ($models as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['title'] = $value['title'];
            $data[$key]['message'] = $value['message'];
            $data[$key]['data'] = !empty($value['data']) ? json_decode($value['data'], true) : '';
            $data[$key]['user_id'] = $value['user_id'];
            $data[$key]['image'] = \yii\helpers\Url::base(true) . '/img_assets/fan-rating.png';
            $data[$key]['created_at'] = strtotime(\Yii::$app->time->asTime($value['created_at']));
            $data[$key]['format_time'] = \Yii::$app->time->format_time(strtotime(\Yii::$app->time->asTime($value['created_at'])));
            $data[$key]['format_date'] = \Yii::$app->time->asDate($value['created_at']);
        }
        return [
            'rows' => $data,
            'pagination' => $pagination,
        ];
    }

    /**
     * Gets query for [[FromUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
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
}
