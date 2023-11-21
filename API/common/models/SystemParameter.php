<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "system_parameter".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $value
 * @property int $is_editable_for_client
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class SystemParameter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_parameter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['is_editable_for_client', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'value' => Yii::t('app', 'Value'),
            'is_editable_for_client' => Yii::t('app', 'Is Editable For Client'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
