<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season_team_player".
 *
 * @property int $id
 * @property int $team_id
 * @property string $photo
 * @property string $name
 * @property int|null $api_player_id
 * @property string|null $api_response
 *
 * @property SeasonTeam $team
 * @property UserMatchVote[] $userMatchVotes
 */
class SeasonTeamPlayer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_team_player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'photo', 'name'], 'required'],
            [['team_id', 'api_player_id','number'], 'integer'],
            [['photo', 'api_response'], 'string'],
            [['name'], 'string', 'max' => 100],
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
            'team_id' => 'Team ID',
            'photo' => 'Photo',
            'name' => 'Name',
            'api_player_id' => 'Api Player ID',
            'api_response' => 'Api Response',
        ];
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
     * Gets query for [[UserMatchVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserMatchVotes()
    {
        return $this->hasMany(UserMatchVote::className(), ['player_id' => 'id']);
    }
}