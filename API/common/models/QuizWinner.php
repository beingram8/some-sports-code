<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz_winner".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $winner_user_id
 * @property int $rank_no
 * @property int|null $created_at
 *
 * @property Quiz $quiz
 * @property UserData $winnerUser
 */
class QuizWinner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_winner';
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
            [['quiz_id', 'winner_user_id'], 'required'],
            [['quiz_id', 'winner_user_id', 'created_at', 'total_correct_ans', 'is_winner'], 'integer'],

            [['is_winner'], 'default', 'value' => 0],
            [['total_correct_ans'], 'default', 'value' => 0],

            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'id']],
            [['winner_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserData::className(), 'targetAttribute' => ['winner_user_id' => 'user_id']],
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
            'winner_user_id' => 'Winner User ID',
            'created_at' => 'Created At',
        ];
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
     * Gets query for [[WinnerUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWinnerUser()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'winner_user_id']);
    }
}