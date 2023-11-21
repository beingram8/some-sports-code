<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season_match".
 *
 * @property int $id
 * @property int $season
 * @property int $league_id
 * @property int $match_timestamp
 * @property int|null $winner_team_id
 * @property string $match_date
 * @property int $team_home_id
 * @property int $team_away_id
 * @property string $match_ground
 * @property string|null $match_city
 * @property int|null $goal_of_home_team
 * @property int|null $goal_of_away_team
 * @property int $is_match_finished 1= Finished 0 = Not Finished -1 = Cancelled

 * @property int $vote_closing_at
 * @property int|null $api_match_id
 * @property string|null $api_response
 *
 * @property SeasonTeam $teamAway
 * @property SeasonTeam $teamHome
 * @property SeasonLeague $league
 * @property Season $season0
 * @property UserMatchVote[] $userMatchVotes
 */
class SeasonMatch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_match';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season', 'league_id', 'match_timestamp', 'team_home_id', 'team_away_id', 'api_match_id'], 'required'],
            [['season', 'league_id', 'match_timestamp', 'team_home_id', 'team_away_id', 'goal_of_home_team', 'goal_of_away_team', 'is_match_finished', 'vote_closing_at', 'api_match_id'], 'integer'],
            [['match_date', 'winner_team_id', 'is_vote_enabled', 'is_point_calculated', 'match_url', 'match_day'], 'safe'],
            [['api_response'], 'string'],
            [['match_ground'], 'string', 'max' => 100],
            [['match_city'], 'string', 'max' => 30],
            [['team_away_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeam::className(), 'targetAttribute' => ['team_away_id' => 'id']],
            [['team_home_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeam::className(), 'targetAttribute' => ['team_home_id' => 'id']],
            [['league_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonLeague::className(), 'targetAttribute' => ['league_id' => 'id']],
            [['season'], 'exist', 'skipOnError' => true, 'targetClass' => Season::className(), 'targetAttribute' => ['season' => 'season']],
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
            'league_id' => 'League ID',
            'match_timestamp' => 'Match Timestamp',
            'winner_team_id' => 'Winner Team ID',
            'match_date' => 'Match Date',
            'team_home_id' => 'Team Home ID',
            'team_away_id' => 'Team Away ID',
            'match_ground' => 'Match Ground',
            'match_city' => 'Match City',
            'goal_of_home_team' => 'Goal Of Home Team',
            'goal_of_away_team' => 'Goal Of Away Team',
            'is_match_finished' => 'Is Match Finished',
            'vote_closing_at' => 'Vote Closing At',
            'api_match_id' => 'Api Match ID',
            'api_response' => 'Api Response',
        ];
    }
    public function getMatchPlayers()
    {
        return $this->hasMany(SeasonMatchPlayer::className(), ['match_id' => 'id']);
    }
    /**
     * Gets query for [[TeamAway]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamAway()
    {
        return $this->hasOne(SeasonTeam::className(), ['id' => 'team_away_id']);
    }

    /**
     * Gets query for [[TeamHome]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamHome()
    {
        return $this->hasOne(SeasonTeam::className(), ['id' => 'team_home_id']);
    }

    /**
     * Gets query for [[League]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLeague()
    {
        return $this->hasOne(SeasonLeague::className(), ['id' => 'league_id']);
    }

    /**
     * Gets query for [[Season0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['season' => 'season']);
    }
    public function getSeason0()
    {
        return $this->hasOne(Season::className(), ['season' => 'season']);
    }
    /**
     * Gets query for [[UserMatchVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserMatchVotes()
    {
        return $this->hasMany(UserMatchVote::className(), ['match_id' => 'id']);
    }

    public function getUserMatchVote()
    {
        return $this->hasOne(UserMatchVote::className(), ['match_id' => 'id']);
    }
}
