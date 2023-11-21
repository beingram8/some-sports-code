<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parent_confirmation".
 *
 * @property int $user_id
 * @property int $is_confirm
 * @property string|null $document
 * @property int|null $created_at
 *
 * @property User $user
 */
class ParentConfirmation extends \yii\db\ActiveRecord
{

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
    public static function tableName()
    {
        return 'parent_confirmation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'is_confirm', 'created_at','id'], 'integer'],
            ['document', 'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Limit is 10MB'],
            [['document'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'is_confirm' => 'Is Confirm',
            'document' => 'Document',
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
}
