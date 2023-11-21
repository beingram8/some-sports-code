<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season".
 *
 * @property int $season
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property int $is_expired
 *
 * @property SeasonLeague[] $seasonLeagues
 * @property SeasonMatch[] $seasonMatches
 * @property UserMatchVote[] $userMatchVotes
 */
class Season extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season', 'title', 'start_date', 'end_date'], 'required'],
            [['season', 'is_expired'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['title'], 'string', 'max' => 20],
            [['season'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'season' => 'Season',
            'title' => 'Title',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'is_expired' => 'Is Expired',
        ];
    }

    /**
     * Gets query for [[SeasonLeagues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonLeagues()
    {
        return $this->hasMany(SeasonLeague::className(), ['season' => 'season']);
    }

    /**
     * Gets query for [[SeasonMatches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonMatches()
    {
        return $this->hasMany(SeasonMatch::className(), ['season' => 'season']);
    }

    /**
     * Gets query for [[UserMatchVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserMatchVotes()
    {
        return $this->hasMany(UserMatchVote::className(), ['season' => 'season']);
    }
}