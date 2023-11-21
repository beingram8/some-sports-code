<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teasing_room_likes".
 *
 * @property int $id
 * @property int $teasing_id
 * @property int $user_id
 * @property string $created_duration
 *
 * @property UserData $user
 * @property TeasingRoom $teasing
 */
class TeasingRoomLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teasing_room_likes';
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
            [['teasing_id', 'user_id'], 'required'],
            [['teasing_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['teasing_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeasingRoom::className(), 'targetAttribute' => ['teasing_id' => 'id']],
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
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
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

    /**
     * Gets query for [[Teasing]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasing()
    {
        return $this->hasOne(TeasingRoom::className(), ['id' => 'teasing_id']);
    }
}
