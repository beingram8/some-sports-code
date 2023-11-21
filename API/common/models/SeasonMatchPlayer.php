<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season_match_players".
 *
 * @property int $id
 * @property int $match_id
 * @property int $team_id
 * @property int $player_id
 * @property int $type 1 = Player | 2 = Substitue | 3 = Coach
 * @property int|null $created_at
 *
 * @property SeasonMatch $match
 * @property SeasonTeamPlayer $player
 * @property SeasonTeam $team
 */
class SeasonMatchPlayer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_match_players';
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
            [['match_id', 'team_id', 'player_id'], 'required'],
            ['position', 'safe'],
            [['match_id', 'team_id', 'player_id', 'type', 'created_at'], 'integer'],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonMatch::className(), 'targetAttribute' => ['match_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeamPlayer::className(), 'targetAttribute' => ['player_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeam::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'team_id' => 'Team ID',
            'player_id' => 'Player ID',
            'type' => 'Type',
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
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(SeasonTeamPlayer::className(), ['id' => 'player_id']);
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

    public function getPlayerVote()
    {
        return $this->hasOne(UserMatchVote::className(), ['player_id' => 'player_id'])
            ->andOnCondition(['imageable_type' => 'Person']);
    }

    public function getVote()
    {
        return $this->hasOne(UserMatchVote::className(), ['player_id' => 'player_id']);
    }
}