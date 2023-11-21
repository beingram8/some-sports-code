<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_point_transaction".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type 1=Match Point,2= Winning,3=Buying Point
 * @property string|null $remark
 * @property int $transaction_type
 * @property float|null $points
 * @property int|null $match_id
 * @property int|null $team_id
 * @property int|null $player_id
 * @property int|null $created_at
 *
 * @property SeasonMatch $match
 * @property SeasonTeamPlayer $player
 * @property SeasonTeam $team
 * @property UserData $user
 */
class UserPointTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_point_transaction';
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
            [['user_id', 'type', 'points', 'transaction_type'], 'required'],
            [['user_id', 'type', 'transaction_type', 'match_id', 'team_id', 'player_id', 'created_at'], 'integer'],
            [['points'], 'number'],
            [['remark'], 'string', 'max' => 255],
            [['match_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonMatch::className(), 'targetAttribute' => ['match_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeamPlayer::className(), 'targetAttribute' => ['player_id' => 'id']],
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
            'user_id' => 'User',
            'type' => 'Type',
            'remark' => 'Remark',
            'transaction_type' => 'Transaction Type',
            'points' => 'Points',
            'match_id' => 'Match ID',
            'team_id' => 'Team ID',
            'player_id' => 'Player ID',
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