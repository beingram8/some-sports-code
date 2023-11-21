<?php

namespace app\models;

use common\models\User;
use yii\base\Model;

/**
 * User Edit form
 */
class UserEditForm extends Model
{
    public $first_name;
    public $last_name;
    public $username;
    public $gender;
    public $birth_date;
    public $birth_year;
    public $city_id;
    public $education_id;
    public $job_id;
    public $country_id;
    public $team_id;
    public $league_id;
    public $fan;
    public $phone;
    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'username', 'gender', 'birth_date'], 'required'],
            [['gender', 'birth_year', 'city_id', 'education_id', 'job_id'], 'integer'],
            [['email', 'fan', 'team_id', 'point', 'token', 'country_id'], 'safe'],
            [['first_name', 'last_name', 'username', 'phone'], 'string', 'length' => [3, 25]],
            [['username'], 'string', 'max' => 50],
            ['team_id', 'exist', 'targetClass' => '\common\models\SeasonTeam', 'message' => 'Invalid Team.', 'targetAttribute' => 'id'],
            ['league_id', 'exist', 'targetClass' => '\common\models\SeasonLeague', 'message' => 'Invalid League.', 'targetAttribute' => 'id'],
            [
                'username',
                'unique',
                'targetClass' => '\common\models\UserData',
                'filter' => ['!=', 'user_id', \Yii::$app->user->identity->id],
                'message' => \Yii::t('app', 'Questo nome utente � gi� stato preso.'),
            ],
        ];
    }
}
