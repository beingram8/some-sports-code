<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ms_languages".
 *
 * @property int $id
 * @property string|null $language
 * @property int|null $flagDocId
 * @property string|null $locale
 *
 * @property Country[] $Country
 * @property Document $flagDoc
 * @property User[] $users
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['language_id', 'language', 'country', 'name', 'name_ascii', 'status'], 'required'],
            [['status'], 'integer'],
            [['language_id'], 'string', 'max' => 5],
            [['language', 'country'], 'string', 'max' => 3],
            [['name', 'name_ascii'], 'string', 'max' => 32],
            [['language_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'language_id' => Yii::t('app', 'Language ID'),
            'language' => Yii::t('app', 'Language'),
            'country' => Yii::t('app', 'Country'),
            'name' => Yii::t('app', 'Name'),
            'name_ascii' => Yii::t('app', 'Name Ascii'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

}