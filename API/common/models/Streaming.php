<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "streaming".
 *
 * @property int $id
 * @property string $title
 * @property int $is_live
 * @property string $thumb_img
 * @property int|null $created_at
 */
class Streaming extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'streaming';
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
            [['title', 'is_live'], 'required'],
            ['thumb_img', 'safe'],
            [['is_live', 'created_at'], 'integer'],
            // [['thumb_img'], 'string'],
            // [['thumb_img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg'],
            ['thumb_img', 'required'],
            [['thumb_img'], 'safe'],
            [['title'], 'string', 'max' => 50],

            ['is_external', 'required', 'on' => 'create', 'when' => function ($model) {
                return $model->is_live == 0;
            }, 'whenClient' => "function (attribute, value) {
                return $('#streaming-is_live').val() == 0;
            }"],

            ['video', 'required', 'on' => 'create', 'when' => function ($model) {
                return $model->is_external == 1;
            }, 'whenClient' => "function (attribute, value) {
                return $('#streaming-is_external').val() == 1;
            }"],

            ['external_link', 'required', 'on' => 'create', 'when' => function ($model) {
                return $model->is_external == 2;
            }, 'whenClient' => "function (attribute, value) {
                return $('#streaming-is_external').val() == 2;
            }"],

            ['video', 'file', 'skipOnEmpty' => true, 'extensions' => 'mkv, mp4', 'maxSize' => 1024 * 1024 * 100],
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
            'is_live' => 'Is Live',
            'thumb_img' => 'Thumb Img',
            'created_at' => 'Created At',
            'is_external' => 'Is external Link',
        ];
    }
}
