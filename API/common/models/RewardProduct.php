<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reward_product".
 *
 * @property int $id
 * @property int $reward_category_id
 * @property string $name
 * @property int $buying_token
 * @property string $reward_img_url
 * @property string|null $reward_description
 * @property string $description
 * @property int $is_physical
 *
 * @property RewardCode[] $rewardCodes
 * @property RewardCategory $rewardCategory
 */
class RewardProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reward_category_id', 'name', 'buying_token', 'description', 'order_no'], 'required'],
            [['reward_category_id', 'buying_token', 'is_physical', 'order_no'], 'integer'],

            [['order_no'], 'unique'],

            [['reward_img_url'], 'required'],
            ['reward_img_url', 'safe'],
            [['reward_description'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['buying_token'], 'string', 'max' => 5],

            [['reward_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => RewardCategory::className(), 'targetAttribute' => ['reward_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reward_category_id' => 'Select',
            'name' => 'Name',
            'buying_token' => 'Buying Token',
            'reward_img_url' => 'Reward Img',
            'reward_description' => 'Redeem Description',
            'description' => 'Description',
            'is_physical' => 'Is Physical',
        ];
    }

    /**
     * Gets query for [[RewardCodes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRewardCodes()
    {
        return $this->hasMany(RewardCode::className(), ['reward_id' => 'id']);
    }

    /**
     * Gets query for [[RewardCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRewardCategory()
    {
        return $this->hasOne(RewardCategory::className(), ['id' => 'reward_category_id']);
    }

}
