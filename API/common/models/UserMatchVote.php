<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_match_vote".
 *
 * @property int $id
 * @property int $season
 * @property int $match_id
 * @property int $team_id
 * @property int $player_id
 * @property int $user_id
 * @property float $vote
 * @property int|null $created_at
 *
 * @property SeasonMatch $match
 * @property SeasonTeamPlayer $player
 * @property Season $season0
 * @property SeasonTeam $team
 * @property UserData $user
 */
class UserMatchVote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_match_vote';
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['created_at'],
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
            [['season', 'match_id', 'team_id', 'player_id', 'user_id', 'vote'], 'required'],
            [['season', 'match_id', 'team_id', 'player_id', 'user_id', 'created_at'], 'integer'],
            [['vote'], 'number'],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonMatch::className(), 'targetAttribute' => ['match_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeamPlayer::className(), 'targetAttribute' => ['player_id' => 'id']],
            [['season'], 'exist', 'skipOnError' => true, 'targetClass' => Season::className(), 'targetAttribute' => ['season' => 'season']],
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
            'id' => Yii::t('app', 'ID'),
            'season' => Yii::t('app', 'Season'),
            'match_id' => Yii::t('app', 'Match ID'),
            'team_id' => Yii::t('app', 'Team ID'),
            'player_id' => Yii::t('app', 'Player ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'vote' => Yii::t('app', 'Vote'),
            'created_at' => Yii::t('app', 'Created At'),
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
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(SeasonTeamPlayer::className(), ['id' => 'player_id']);
    }

    /**
     * Gets query for [[Season0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeason0()
    {
        return $this->hasOne(Season::className(), ['season' => 'season']);
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