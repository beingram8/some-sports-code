<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "refer_and_earn".
 *
 * @property int $id
 * @property int $refer_user_id
 * @property string $code
 * @property int $created_at
 * @property int|null $code_used
 *
 * @property User $referUser
 */
class ReferAndEarn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refer_and_earn';
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
            [['refer_user_id', 'code'], 'required'],
            [['refer_user_id', 'created_at', 'code_used'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['code'], 'unique'],
            [['refer_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['refer_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'refer_user_id' => 'Refer User ID',
            'code' => 'Code',
            'created_at' => 'Created At',
            'code_used' => 'Code Used',
        ];
    }

    /**
     * Gets query for [[ReferUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferUser()
    {
        return $this->hasOne(User::className(), ['id' => 'refer_user_id']);
    }
}
