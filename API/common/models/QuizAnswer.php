<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz_answer".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $question_id
 * @property int $user_id
 * @property string $selected_option
 * @property int $is_correct
 * @property int|null $created_at
 *
 * @property QuizQuestion $question
 * @property Quiz $quiz
 * @property UserData $user
 */
class QuizAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_answer';
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
            [['quiz_id', 'question_id', 'user_id', 'selected_option'], 'required'],
            [['quiz_id', 'question_id', 'user_id', 'is_correct', 'created_at'], 'integer'],
            [['selected_option'], 'string', 'max' => 50],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuizQuestion::className(), 'targetAttribute' => ['question_id' => 'id']],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'id']],
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
            'quiz_id' => 'Quiz ID',
            'question_id' => 'Question ID',
            'user_id' => 'User ID',
            'selected_option' => 'Selected Option',
            'is_correct' => 'Is Correct',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(QuizQuestion::className(), ['id' => 'question_id']);
    }

    /**
     * Gets query for [[Quiz]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['id' => 'quiz_id']);
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
