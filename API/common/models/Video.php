<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string $title
 * @property string $video_url
 * @property string|null $description
 * @property int|null $created_at
 */
class Video extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video';
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
            [['title','is_external'], 'required'],

            [['thumb_img'], 'required'],
            // [['video_url', ], 'required' , 'on' => 'create'],

            [['video_url'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4,mkv,webm', 'maxSize' => 1024 * 1024 * 100],

            // [['thumb_img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg'],
            ['video_url', 'required', 'on' => 'create', 'when' => function ($model) {
                return $model->is_external == 0;
            }, 'whenClient' => "function (attribute, value) {
                return $('#video-is_external').val() == 0;
            }"],

            ['external_link', 'required', 'when' => function ($model) {
                return $model->is_external == 1;
            }, 'whenClient' => "function (attribute, value) {
                return $('#video-is_external').val() == 1;
            }"],

            [['thumb_img'], 'safe'],

            [['description','external_link'], 'string', 'max' => 500],
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'video_url' => 'Video',
            'thumb_img' => 'Video Thumb',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    public function getList()
    {
        $query = Video::find()->orderBy('id DESC')->asArray();
        $page = $this->page > 0 ? ($this->page - 1) : 0;
        $pageSize = (int) $this->per_page;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => true,
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => 6,
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
            $data[$key]['title'] = $value['title'];
            $data[$key]['video_url'] = $value['is_external'] == 1 ? $value['external_link'] : $value['video_url'];
            $data[$key]['description'] = $value['description'];
            $data[$key]['created_at'] = Yii::$app->general->format_date($value['created_at']);
            $data[$key]['thumb_img'] = $value['thumb_img'];

        }
        return array('rows' => $data, 'pagination' => $pagination);
    }
}
