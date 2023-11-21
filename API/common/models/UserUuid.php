<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_uuid".
 *
 * @property int $user_id
 * @property string $uuid
 *
 * @property User $user
 */
class UserUuid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_uuid';
    }

    public static function primaryKey()
    {
        return ["uuid"];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'uuid'], 'required'],
            [['user_id'], 'integer'],
            [['uuid'], 'string'],
            ['platform', 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'platform' => Yii::t('app', 'Platform'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserData()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'user_id']);
    }
}
