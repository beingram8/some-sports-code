<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_token_transaction".
 *
 * @property int $id
 * @property int $user_id
 * @property int $transaction_type 10= Credit 20= Debit
 * @property int $token
 * @property int|null $token_type_id
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property TokenType $tokenType
 * @property UserData $user
 */
class UserTokenTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_token_transaction';
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
            [['user_id', 'transaction_type', 'token'], 'required'],
            [['user_id', 'transaction_type', 'token', 'token_type_id', 'created_at', 'created_by'], 'integer'],
            [['token_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TokenType::className(), 'targetAttribute' => ['token_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['news_like_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_like_id' => 'id']],
            [['news_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_comment_id' => 'id']],
            [['video_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['video_id' => 'id']],
            [['stream_id'], 'exist', 'skipOnError' => true, 'targetClass' => Streaming::className(), 'targetAttribute' => ['stream_id' => 'id']],
            [['remark', 'news_like_id', 'news_comment_id', 'teasing_like_id', 'teasing_comment_id', 'video_id', 'is_profile_setup', 'stream_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'transaction_type' => 'Transaction Type',
            'token' => 'Token',
            'token_type_id' => 'Token Type ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'remark' => 'Remark',
        ];
    }

    /**
     * Gets query for [[TokenType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTokenType()
    {
        return $this->hasOne(TokenType::className(), ['id' => 'token_type_id']);
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
