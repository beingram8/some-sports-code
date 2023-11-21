<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "survey_question_option".
 *
 * @property int $id
 * @property int $survey_id
 * @property int $survey_question_id
 * @property string|null $option_as_text
 * @property string|null $option_as_img
 * @property float|null $result_in_percentage
 * @property int|null $no_users_answered
 *
 * @property Survey $survey
 * @property SurveyQuestion $surveyQuestion
 * @property SurveyUserSelectedOption[] $surveyUserSelectedOptions
 */
class SurveyQuestionOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_question_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['survey_id', 'survey_question_id'], 'required'],
            [['survey_id', 'survey_question_id', 'no_users_answered'], 'integer'],
            [['option_as_img'], 'string'],
            [['result_in_percentage'], 'number'],
            [['option_as_text'], 'string', 'max' => 30],
            [['survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => Survey::className(), 'targetAttribute' => ['survey_id' => 'id']],
            [['survey_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyQuestion::className(), 'targetAttribute' => ['survey_question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_id' => 'Survey ID',
            'survey_question_id' => 'Survey Question ID',
            'option_as_text' => 'Option As Text',
            'option_as_img' => 'Option As Img',
            'result_in_percentage' => 'Result In Percentage',
            'no_users_answered' => 'No Users Answered',
        ];
    }

    /**
     * Gets query for [[Survey]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurvey()
    {
        return $this->hasOne(Survey::className(), ['id' => 'survey_id']);
    }

    /**
     * Gets query for [[SurveyQuestion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyQuestion()
    {
        return $this->hasOne(SurveyQuestion::className(), ['id' => 'survey_question_id']);
    }

    /**
     * Gets query for [[SurveyUserSelectedOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyUserSelectedOptions()
    {
        return $this->hasMany(SurveyUserSelectedOption::className(), ['survey_option_id' => 'id']);
    }

    public function getUserOption()
    {
        return $this->hasOne(SurveyUserSelectedOption::className(), ['survey_option_id' => 'id']);
    }
}
