<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz".
 *
 * @property int $id
 * @property string $quiz_title
 * @property string|null $quiz_description
 * @property int $start_date
 * @property int $end_date
 * @property int $is_active
 * @property int|null $created_at
 *
 * @property QuizAnswer[] $quizAnswers
 * @property QuizQuestion[] $quizQuestions
 * @property QuizWinner[] $quizWinners
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz';
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
            [['quiz_title', 'start_date', 'end_date'], 'required'],

            ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>'],

            [['is_active'], 'default', 'value' => '0'],

            [['quiz_description'], 'string'],
            [['is_active', 'created_at'], 'integer'],
            [['quiz_title'], 'string', 'max' => 70],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_title' => 'Quiz Title',
            'quiz_description' => 'Quiz Description',
            'start_date' => 'Start Date (Set in UTC timezone)',
            'end_date' => 'End Date (Set in UTC timezone)',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[QuizAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizAnswers()
    {
        return $this->hasMany(QuizAnswer::className(), ['quiz_id' => 'id']);
    }

    /**
     * Gets query for [[QuizQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizQuestions()
    {
        return $this->hasMany(QuizQuestion::className(), ['quiz_id' => 'id']);
    }

    /**
     * Gets query for [[QuizWinners]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizWinners()
    {
        return $this->hasMany(QuizWinner::className(), ['quiz_id' => 'id']);
    }
}