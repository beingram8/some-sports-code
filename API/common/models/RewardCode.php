<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reward_code".
 *
 * @property int $id
 * @property int $reward_id
 * @property string $reward_code
 * @property int|null $user_id
 * @property int|null $updated_at
 *
 * @property RewardProduct $reward
 * @property UserData $user
 */
class RewardCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_code';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['reward_id', 'reward_code'], 'required'],
            [['reward_id', 'user_id', 'updated_at'], 'integer'],
            [['reward_code'], 'string', 'max' => 100],
            [['reward_id'], 'exist', 'skipOnError' => true, 'targetClass' => RewardProduct::className(), 'targetAttribute' => ['reward_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reward_id' => 'Reward ID',
            'reward_code' => 'Reward Code',
            'user_id' => 'User ID',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Reward]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReward()
    {
        return $this->hasOne(RewardProduct::className(), ['id' => 'reward_id']);
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
}
