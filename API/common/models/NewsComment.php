<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "news_comment".
 *
 * @property int $id
 * @property int $news_id
 * @property int $user_id
 * @property string $comment_text
 * @property int|null $created_at
 *
 * @property News $news
 * @property UserData $user
 */
class NewsComment extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_comment';
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
            [['news_id', 'user_id', 'comment_text'], 'required'],
            [['news_id', 'user_id', 'created_at'], 'integer'],
            [['comment_text'], 'string', 'max' => 255],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            ['page', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'news_id' => Yii::t('app', 'News ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'comment_text' => Yii::t('app', 'Comment Text'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[News]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
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

    public function getCommentList($id)
    {
        $query = NewsComment::find()->where(['news_id' => $id])->orderBy('id DESC');
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
            $teamData = Yii::$app->team->getTeam($value->user->team_id);
            $data[$key]['id'] = $value['id'];
            $data[$key]['user_id'] = $value['user_id'];
            $data[$key]['username'] = Yii::$app->userData->username($value['user_id']);
            $data[$key]['user_photo'] = Yii::$app->userData->photo($value['user_id']);
            $data[$key]['team_icon'] = !empty($teamData->logo) ? $teamData->logo : null;
            $data[$key]['comment_text'] = $value['comment_text'];
            $data[$key]['created_at'] = \Yii::$app->general->timeAgo($value['created_at']);
        }
        return array('rows' => $data, 'pagination' => $pagination);
    }

}
