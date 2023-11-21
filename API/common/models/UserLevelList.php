<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_level_list".
 *
 * @property int $id
 * @property string $level
 * @property int $point
 * @property string|null $description
 */
class UserLevelList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_level_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'point','level_price'], 'required'],
            [['point'], 'integer'],
            [['level_price'], 'number'],
            [['level'], 'string', 'max' => 30],
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
            'level' => 'Level',
            'point' => 'Point',
            'description' => 'Description',
        ];
    }
}
