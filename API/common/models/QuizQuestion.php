<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "quiz_question".
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $question
 * @property string $correct_ans
 * @property string $option_1
 * @property string $option_2
 * @property string $option_3
 * @property string $option_4
 *
 * @property QuizAnswer[] $quizAnswers
 * @property Quiz $quiz
 */
class QuizQuestion extends \yii\db\ActiveRecord
{

    public $page = 1;
    public $per_page = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'question', 'correct_ans', 'option_1', 'option_2', 'option_3', 'option_4'], 'required'],
            [['quiz_id'], 'integer'],
            [['question'], 'string', 'max' => 150],
            [['correct_ans', 'option_1', 'option_2', 'option_3', 'option_4'], 'string', 'max' => 30],
            [['page', 'per_page'], 'integer'],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'id']],
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
            'question' => 'Question',
            'correct_ans' => 'Correct Ans',
            'option_1' => 'Option 1',
            'option_2' => 'Option 2',
            'option_3' => 'Option 3',
            'option_4' => 'Option 4',
        ];
    }

    /**
     * Gets query for [[QuizAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizAnswers()
    {
        return $this->hasMany(QuizAnswer::className(), ['question_id' => 'id']);
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

    public function getQuestionList($quiz_id)
    {
        $query = \common\models\QuizQuestion::find()->where(['quiz_id' => $quiz_id])->asArray();

        $page = $this->page > 0 ? ($this->page - 1) : 0;
        $pageSize = (int) $this->per_page;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => true,
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
            ],
        ]);

        $models = $provider->getModels();

        $pagination = array_intersect_key(
            (array) $provider->pagination,
            array_flip(
                $paginationParams = [
                    'pageParam',
                    'pageSizeParam',
                    'params',
                    'totalCount',
                    'defaultPageSize',
                    'pageSizeLimit',
                ]
            )
        );

        $totalPage = $pagination['totalCount'] / $pageSize;
        $pagination['totalPage'] = $totalPage;
        $pagination['currentPage'] = $this->page;
        $pagination['isMore'] = $totalPage <= $this->page ? false : true;
        $data = [];

        foreach ($models as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['question'] = $value['question'];
            $data[$key]['options'][0]['option'] = $value['option_1'];
            $data[$key]['options'][1]['option'] = $value['option_2'];
            $data[$key]['options'][2]['option'] = $value['option_3'];
            $data[$key]['options'][3]['option'] = $value['option_4'];
        }
        return array('rows' => $data, 'pagination' => $pagination);
    }
}