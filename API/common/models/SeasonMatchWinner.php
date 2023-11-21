<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season_match_winner".
 *
 * @property int $id
 * @property int $season
 * @property int $match_id
 * @property int $team_id
 * @property int $user_id
 * @property float $points
 * @property int $rank
 * @property int|null $created_at
 *
 * @property SeasonMatch $match
 * @property SeasonTeam $team
 * @property UserData $user
 */
class SeasonMatchWinner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_match_winner';
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
            [['season', 'match_id', 'team_id', 'user_id', 'points', 'rank'], 'required'],
            [['season', 'match_id', 'team_id', 'user_id', 'rank', 'created_at'], 'integer'],
            [['points'], 'number'],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonMatch::className(), 'targetAttribute' => ['match_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeam::className(), 'targetAttribute' => ['team_id' => 'id']],
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
            'season' => 'Season',
            'match_id' => 'Match ID',
            'team_id' => 'Team ID',
            'user_id' => 'User ID',
            'points' => 'Points',
            'rank' => 'Rank',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Match]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasOne(SeasonMatch::className(), ['id' => 'match_id']);
    }

    /**
     * Gets query for [[Team]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(SeasonTeam::className(), ['id' => 'team_id']);
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
