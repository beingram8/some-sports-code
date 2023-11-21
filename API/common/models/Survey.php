<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "survey".
 *
 * @property int $id
 * @property string $sponsored_by
 * @property string|null $sponsor_adv
 * @property int $is_active
 * @property int $start_date
 * @property int $end_date
 * @property string|null $description
 * @property int $created_at
 *
 * @property SurveyQuestion[] $surveyQuestions
 * @property SurveyQuestionOption[] $surveyQuestionOptions
 * @property SurveyUserSelectedOption[] $surveyUserSelectedOptions
 */
class Survey extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey';
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
            [['sponsored_by', 'start_date', 'end_date'], 'required'],

            ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>'],

            [['sponsor_adv'], 'required'],
            // [['sponsor_adv'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg'],

            [['sponsor_adv'], 'safe'],

            [['is_active'], 'default', 'value' => '0'],

            [['description'], 'string'],
            [['is_active', 'created_at'], 'integer'],
            [['sponsored_by'], 'string', 'max' => 50],
            [['page', 'per_page'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sponsored_by' => 'Sponsored By',
            'sponsor_adv' => 'Sponsor Adv',
            'is_active' => 'Is Active',
            'start_date' => 'Start Date (Set in UTC timezone)',
            'end_date' => 'End Date (Set in UTC timezone)',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[SurveyQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::className(), ['survey_id' => 'id']);
    }

    /**
     * Gets query for [[SurveyQuestionOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyQuestionOptions()
    {
        return $this->hasMany(SurveyQuestionOption::className(), ['survey_id' => 'id']);
    }

    /**
     * Gets query for [[SurveyUserSelectedOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyUserSelectedOptions()
    {
        return $this->hasMany(SurveyUserSelectedOption::className(), ['survey_id' => 'id']);
    }

}