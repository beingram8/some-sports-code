<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_social_auth".
 *
 * @property string $provider_key
 * @property int $user_id
 * @property int $provider_type 1 = Facebook 2= Google
 */
class UserSocialAuth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_social_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provider_key', 'user_id', 'provider_type'], 'required'],
            [['user_id', 'provider_type'], 'integer'],
            [['provider_key'], 'string', 'max' => 250],
            [['user_id'], 'unique'],
            [['provider_key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'provider_key' => Yii::t('app', 'Provider Key'),
            'user_id' => Yii::t('app', 'User ID'),
            'provider_type' => Yii::t('app', 'Provider Type'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
