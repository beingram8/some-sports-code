<?php
namespace backend\models;

use yii\base\Model;

/**
 * FetchLeagueForm form
 */
class FetchLeagueForm extends Model
{
    public $season;
    public $country;
    public $leagues;

    public function rules()
    {
        return [
            [['season', 'country', 'leagues'], 'required'],
            ['leagues', 'safe'],
        ];
    }
}