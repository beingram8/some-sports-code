<?php

namespace common\models;

use common\models\SurveyUserSelectedOption;
use Yii;

/**
 * This is the model class for table "survey_question".
 *
 * @property int $id
 * @property int $survey_id
 * @property string $question
 * @property int $option_type 1 = Image or 0 =  Text
 *
 * @property Survey $survey
 * @property SurveyQuestionOption[] $surveyQuestionOptions
 * @property SurveyUserSelectedOption[] $surveyUserSelectedOptions
 */
class SurveyQuestion extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    public $option_text_1;
    public $option_text_2;
    public $option_text_3;
    public $option_text_4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['survey_id', 'question'], 'required'],
            [['option_text_1', 'option_text_2', 'option_text_3', 'option_text_4'], 'string', 'max' => 100],
            [['option_text_1', 'option_text_2', 'option_text_3', 'option_text_4'], 'required'],
            [['survey_id'], 'integer'],
            [['question'], 'string', 'max' => 120],
            [['survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => Survey::className(), 'targetAttribute' => ['survey_id' => 'id']],
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
            'question' => 'Question',
            'option_type' => 'Option Type',
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
     * Gets query for [[SurveyQuestionOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyQuestionOptions()
    {
        return $this->hasMany(SurveyQuestionOption::className(), ['survey_question_id' => 'id']);
    }

    /**
     * Gets query for [[SurveyUserSelectedOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyUserSelectedOptions()
    {
        return $this->hasMany(SurveyUserSelectedOption::className(), ['survey_question_id' => 'id']);
    }

    public function optionValues($model, $key)
    {
        $query = SurveyQuestionOption::find()->where(['survey_question_id' => $model->id])->all();
        if ($model->option_type == 1) {
            $value = \yii\helpers\ArrayHelper::getcolumn($query, 'option_as_img');
        } else {
            $value = \yii\helpers\ArrayHelper::getcolumn($query, 'option_as_text');
        }

        return isset($value[$key]) ? $value[$key] : "";
    }

    public function getQuestionList($survey_id)
    {
        $models = \common\models\SurveyQuestion::find()->joinWith('surveyQuestionOptions')->where(['survey_question.survey_id' => $survey_id])->asArray()->all();

        $data = [];

        foreach ($models as $key => $value) {
            $data[$key]['question_id'] = $value['id'];
            $data[$key]['question'] = $value['question'];
            $data[$key]['option_type'] = $value['option_type'] == 1 ? 'Image' : 'Text';
            $option_array = [];
            if (!empty($value['surveyQuestionOptions'])) {
                foreach ($value['surveyQuestionOptions'] as $option) {
                    $data1['option_id'] = $option['id'];
                    $data1['options'] = $value['option_type'] == 1 ? $option['option_as_img'] : $option['option_as_text'];
                    array_push($option_array, $data1);
                }
            }
            $data[$key]['options'] = $option_array;
        }
        return array('rows' => $data);
    }

    public function graphData($id)
    {
        $query = SurveyQuestionOption::find()->joinWith(['surveyQuestion', 'userOption'])
            ->where(['survey_user_selected_option.survey_question_id' => $id])->all();
        $graphData = [];
        foreach ($query as $k => $data) {
            if ($data->surveyQuestion->option_type == 1) {
                $tempData = [
                    'name' => 'Option-' . ($k + 1),
                    'y' => floatval(SurveyUserSelectedOption::find()->where(['survey_id' => $data['survey_id'], 'survey_question_id' => $data['userOption']['survey_question_id'], 'survey_option_id' => $data['userOption']['survey_option_id']])->count()),
                ];
            } else {
                $query = floatval(SurveyUserSelectedOption::find()->where(['survey_id' => $data['survey_id'], 'survey_question_id' => $data['userOption']['survey_question_id'], 'survey_option_id' => $data['userOption']['survey_option_id']])->count());
                $tempData = [
                    'name' => $data->option_as_text,
                    'y' => floatval(SurveyUserSelectedOption::find()->where(['survey_id' => $data['survey_id'], 'survey_question_id' => $data['userOption']['survey_question_id'], 'survey_option_id' => $data['userOption']['survey_option_id']])->count()),
                    'drilldown' => $data->option_as_text,
                ];
            }
            array_push($graphData, $tempData);
        }
        return $graphData;
    }
}