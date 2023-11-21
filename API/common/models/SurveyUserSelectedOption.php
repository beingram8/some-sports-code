<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "survey_user_selected_option".
 *
 * @property int $id
 * @property int $survey_id
 * @property int $survey_question_id
 * @property int $survey_option_id
 * @property int $user_id
 *
 * @property Survey $survey
 * @property SurveyQuestionOption $surveyOption
 * @property SurveyQuestion $surveyQuestion
 * @property UserData $user
 */
class SurveyUserSelectedOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_user_selected_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['survey_id', 'survey_question_id', 'survey_option_id', 'user_id'], 'required'],
            [['survey_id', 'survey_question_id', 'survey_option_id', 'user_id'], 'integer'],
            [['survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => Survey::className(), 'targetAttribute' => ['survey_id' => 'id']],
            [['survey_option_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyQuestionOption::className(), 'targetAttribute' => ['survey_option_id' => 'id']],
            [['survey_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyQuestion::className(), 'targetAttribute' => ['survey_question_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['user_id' => 'user_id']],
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
            'survey_option_id' => 'Survey Option ID',
            'user_id' => 'User ID',
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
     * Gets query for [[SurveyOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyOption()
    {
        return $this->hasOne(SurveyQuestionOption::className(), ['id' => 'survey_option_id']);
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'user_id']);
    }
}