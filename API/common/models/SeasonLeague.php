<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season_league".
 *
 * @property int $id
 * @property int $season
 * @property string $name
 * @property int $type 1= Italian 2 = European
 * @property int|null $api_league_id
 * @property string|null $api_response
 *
 * @property Season $season0
 * @property SeasonMatch[] $seasonMatches
 */
class SeasonLeague extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_league';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season', 'name'], 'required'],
            [['season', 'api_league_id', 'is_active'], 'integer'],
            [['api_response', 'logo'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['country'], 'string', 'max' => 10],
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
            'name' => 'Name',
            'country' => 'Country',
            'api_league_id' => 'Api League ID',
            'api_response' => 'Api Response',
        ];
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
     * Gets query for [[SeasonMatches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonMatches()
    {
        return $this->hasMany(SeasonMatch::className(), ['league_id' => 'id']);
    }
}