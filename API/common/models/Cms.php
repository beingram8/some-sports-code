<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $html_body For Website
 * @property string $app_body For Mobile Application
 * @property string|null $meta_tile
 * @property string|null $meta_keyword
 * @property string|null $meta_description
 * @property int $status 1 = Active 0 = Deactive
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'html_body', 'app_body','status', 'language'], 'required'],
            [['html_body', 'app_body'], 'string'],
            [['status'], 'integer'],
            [['slug', 'title'], 'string', 'max' => 255],
            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language' => 'language']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'language' => 'Language',
            'html_body' => 'Html Body',
            'app_body' => 'App Body',
            'status' => 'Status',
        ];
    }

}
