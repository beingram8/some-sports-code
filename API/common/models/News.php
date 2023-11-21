<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string|null $small_description
 * @property string $body
 * @property string $thumb_img
 * @property string $main_img
 * @property int $is_active
 * @property int $is_general
 * @property int|null $created_at
 *
 * @property NewsAssignedTeam[] $newsAssignedTeams
 * @property NewsComment[] $newsComments
 * @property NewsLike[] $newsLikes
 */
class News extends \yii\db\ActiveRecord
{
    public $team;
    public $most_like;
    public $most_comment;
    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
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
            [['title', 'body', 'is_active'], 'required'],
            ['body', 'string'],
            [['is_active', 'is_general', 'created_at'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 200],
            [['small_description'], 'string', 'max' => 255],
            [['team', 'thumb_img', 'main_img', 'page', 'per_page', 'most_like', 'most_comment'], 'safe'],
            // [['thumb_img', 'main_img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg,jpg', 'maxSize' => 1000000, 'tooBig' => \Yii::t('app', 'Limit is 10MB')],
            [['thumb_img', 'main_img'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'small_description' => Yii::t('app', 'Small Description'),
            'body' => Yii::t('app', 'Body'),
            'thumb_img' => Yii::t('app', 'Thumbnail Image'),
            'main_img' => Yii::t('app', 'Main Image'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_general' => Yii::t('app', 'Is General'),
            'created_at' => Yii::t('app', 'Created At'),
            'team' => Yii::t('app', 'Select Team'),
        ];
    }

    public function getNewsList()
    {
        $data = [];
        $postdata = \Yii::$app->request->post();
        $this->team = is_numeric($this->team) ? $this->team : "";
        if (Yii::$app->user->isGuest) {
            $models = News::find()->where(['is_active' => '1', 'is_general' => 1])
                ->limit(9)->orderBy('news.id DESC');
        } else {
            $team_id = \Yii::$app->user->identity->userData->team_id;
            $news_ids = \common\models\NewsAssignedTeam::find()
                ->where(['team_id' => $team_id])->asArray()->all();
            $news_ids = \yii\helpers\ArrayHelper::getColumn($news_ids, 'news_id');
            $models = News::find()->where(['is_active' => '1'])
                ->andWhere(['OR', ['IN', 'news.id', $news_ids], ['=', 'is_general', 1]])
                ->orderBy('news.id DESC', 'is_general ASC')
                ->limit(9);
        }

        if (!empty($postdata['Team'])) {
            $news_ids = \common\models\NewsAssignedTeam::find()->where(['IN', 'team_id', $postdata['Team']])->asArray()->all();
            $news_ids = \yii\helpers\ArrayHelper::getColumn($news_ids, 'news_id');
            $models = News::find()->where(['is_active' => '1'])
                ->andWhere(['OR', ['IN', 'news.id', $news_ids], ['=', 'is_general', 1]])
                ->orderBy('news.id DESC', 'is_general ASC')
                ->limit(9);
        }
        if (!empty($postdata['created_at']) && $postdata['created_at'] != "false" && !empty($postdata['Team'])) {
            $news_ids = \common\models\NewsAssignedTeam::find()->where(['IN', 'team_id', $postdata['Team']])->asArray()->all();
            $news_ids = \yii\helpers\ArrayHelper::getColumn($news_ids, 'news_id');
            $models->andFilterWhere(['IN', 'news.id', $news_ids]);
        }
        if (!empty($postdata['most_like']) && $postdata['most_like'] == "true") {
            $models->select(['news.*', 'count(news_like.is_like) as like_count'])
                ->joinWith('newsLikes')
                ->groupBy('news_like.news_id')
                ->orderBy('like_count DESC');
        }

        if (!empty($postdata['most_comment']) && $postdata['most_comment'] == "true") {
            $models->select(['news.*', 'count(news_comment.comment_text) as comment_count'])
                ->joinWith('newsComments')
                ->groupBy('news_comment.news_id')
                ->orderBy('comment_count DESC');
        }

        $models = $models->asArray()->all();

        foreach ($models as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['title'] = $value['title'];
            $data[$key]['small_description'] = $value['small_description'];
            $data[$key]['body'] = $value['body'];
            $data[$key]['thumb_img'] = $value['thumb_img'];
            $data[$key]['main_img'] = $value['main_img'];
            $data[$key]['is_active'] = $value['is_active'];
            $data[$key]['is_general'] = $value['is_general'];
            $data[$key]['slug'] = $value['slug'];
            $data[$key]['total_likes'] = \common\models\NewsLike::find()->where(['news_id' => $value['id']])->count();
            $data[$key]['total_comments'] = \common\models\NewsComment::find()->where(['news_id' => $value['id']])->count();
            $data[$key]['created_at'] = \Yii::$app->time->asDatetime($value['created_at']);
        }

        return array('rows' => $data);
    }

    /**
     * Gets query for [[NewsAssignedTeams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsAssignedTeams()
    {
        return $this->hasMany(NewsAssignedTeam::className(), ['news_id' => 'id']);
    }

    /**
     * Gets query for [[NewsComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsComments()
    {
        return $this->hasMany(NewsComment::className(), ['news_id' => 'id']);
    }

    /**
     * Gets query for [[NewsLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsLikes()
    {
        return $this->hasMany(NewsLike::className(), ['news_id' => 'id']);
    }

    public function getLikeCount($news_id)
    {
        return NewsLike::find()->where(['news_id' => $news_id, 'is_like' => 1])->count();
    }
}