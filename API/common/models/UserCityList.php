<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_city_list".
 *
 * @property int $id
 * @property string $name
 *
 * @property UserData[] $userDatas
 */
class UserCityList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_city_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
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
        ];
    }

    /**
     * Gets query for [[UserDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDatas()
    {
        return $this->hasMany(UserData::className(), ['city_id' => 'id']);
    }

    public function allCity()
    {
        $query = UserCityList::find()->asArray()->all();
        $cities = \yii\helpers\ArrayHelper::map($query, 'id', 'name');
        if (!empty($cities)) {
            return $cities;
        } else {
            return ['' => 'No City Found'];
        }
    }
}
