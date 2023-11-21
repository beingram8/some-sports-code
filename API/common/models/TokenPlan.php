<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_plan".
 *
 * @property int $id
 * @property int $token
 * @property float $price
 */
class TokenPlan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'price'], 'required'],
            [['token'], 'integer'],
            [['name'], 'string'],
            [['price','reel_amount'], 'number'],

            ['reel_amount', 'compare','compareAttribute'=>'price','operator'=>'>',
                'message'=>'Reel amount should be greater than price amount', 'type' => 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'price' => 'Price',
        ];
    }
}
