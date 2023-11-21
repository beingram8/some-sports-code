<?php
namespace backend\models;

use yii\base\Model;

/**
 * FetchFixtureForm form
 */
class FetchFixtureForm extends Model
{
    public $season;
    public $league_id;

    public function rules()
    {
        return [
            [['season', 'league_id'], 'required'],
        ];
    }
}