<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_payment_transaction".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status 10 = Success 9 = Pending , 20 = Failed
 * @property float $amount
 * @property string|null $external_response
 * @property string|null $description
 * @property int|null $created_at
 * @property int $created_by
 *
 * @property UserEuropeanFanPackage[] $userEuropeanFanPackages
 * @property UserData $user
 * @property UserSuperFanPackage[] $userSuperFanPackages
 */
class UserPaymentTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_payment_transaction';
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
            [['user_id', 'amount', 'created_by'], 'required'],
            [['user_id', 'status', 'created_at', 'created_by'], 'integer'],
            [['amount'], 'number'],
            [['external_response'], 'string'],
            [['description'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'external_response' => Yii::t('app', 'External Response'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[UserEuropeanFanPackages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserEuropeanFanPackages()
    {
        return $this->hasMany(UserEuropeanFanPackage::className(), ['payment_transaction_id' => 'id']);
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
     * Gets query for [[UserSuperFanPackages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSuperFanPackages()
    {
        return $this->hasMany(UserSuperFanPackage::className(), ['payment_transaction_id' => 'id']);
    }
}
