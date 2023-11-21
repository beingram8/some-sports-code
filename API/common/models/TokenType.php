<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "token_type".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $value
 *
 * @property UserTokenTransaction[] $userTokenTransactions
 */
class TokenType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['value'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[UserTokenTransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokenTransactions()
    {
        return $this->hasMany(UserTokenTransaction::className(), ['token_type_id' => 'id']);
    }
}