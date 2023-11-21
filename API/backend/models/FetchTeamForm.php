<?php
namespace backend\models;

use yii\base\Model;

/**
 * FetchTeamForm form
 */
class FetchTeamForm extends Model
{
    public $season;
    public $league_id;
    public $team_for_which_country_id;
    public $teams;

    public function rules()
    {
        return [
            [['season', 'league_id', 'team_for_which_country_id', 'teams'], 'required'],
            ['teams', 'safe'],
        ];
    }
}