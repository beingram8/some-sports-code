<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teasing_room_reported".
 *
 * @property int $id
 * @property int $teasing_id
 * @property int $reported_user_id
 * @property string $reason
 * @property int|null $created_at
 *
 * @property TeasingRoom $teasing
 * @property UserData $reportedUser
 */
class TeasingRoomReported extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teasing_room_reported';
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
            [['teasing_id', 'reported_user_id', 'reason'], 'required'],
            [['teasing_id', 'reported_user_id', 'created_at'], 'integer'],
            [['reason'], 'string', 'max' => 255],
            [['teasing_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeasingRoom::className(), 'targetAttribute' => ['teasing_id' => 'id']],
            [['reported_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['reported_user_id' => 'user_id']],
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
            'reported_user_id' => 'Reported User ID',
            'reason' => 'Reason',
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
     * Gets query for [[ReportedUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReportedUser()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'reported_user_id']);
    }
}
