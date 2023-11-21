<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int|null $level
 * @property string|null $category
 * @property float|null $log_time
 * @property string|null $prefix
 * @property string|null $message
 */
class ApiResponseLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_response_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Level'),
            'match_id' => Yii::t('app', 'Category'),
            'api_match_id' => Yii::t('app', 'Log Time'),
            'is_main' => Yii::t('app', 'Prefix'),
            'api_team_id' => Yii::t('app', 'Message'),
            'api_team_away_id' => Yii::t('app', 'Message'),
            'team_away_id' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Message'),
        ];
    }
}
