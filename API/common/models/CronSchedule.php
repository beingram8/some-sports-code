<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cron_schedule".
 *
 * @property int $id
 * @property string|null $jobCode
 * @property int $status
 * @property string|null $messages
 * @property string|null $dateCreated
 * @property string|null $dateFinished
 */
class CronSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['messages'], 'string'],
            [['dateCreated', 'dateFinished'], 'safe'],
            [['jobCode'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jobCode' => Yii::t('app', 'Job Code'),
            'status' => Yii::t('app', 'Status'),
            'messages' => Yii::t('app', 'Messages'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateFinished' => Yii::t('app', 'Date Finished'),
        ];
    }
}
