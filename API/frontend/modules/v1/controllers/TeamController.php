<?php

namespace frontend\modules\v1\controllers;

use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class TeamController extends ActiveController
{
    public $modelClass = 'common\models\SeasonTeam';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'list' => ['get'],
                'player-details' => ['get'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'list',
            'list-by-league'
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['player-details'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['player-details'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionList()
    {
        $data = \common\models\SeasonTeam::find()
            ->select(['id', 'name', 'logo', 'is_main_team', 'is_national_team'])
            ->where(['is_active' => 1, 'is_main_team' => 1])
            ->orderBy('name ASC')
            ->asArray()
            ->all();
        return ['status' => true, 'data' => $data];
    }

    public function actionListByLeague($leagueId) {
        $data = \common\models\SeasonTeam::find()
            ->with(["league"])
            // ->select(['id', 'name', 'logo', 'is_main_team', 'is_national_team'])
            ->where(['is_active' => 1, 'is_main_team' => 1, 'league_id' => $leagueId])
            ->orderBy('name ASC')
            ->asArray()
            ->all();
        return ['status' => true, 'data' => $data];
    }

    public function actionPlayerDetails($player_id)
    {
        $playerInfo = \common\models\SeasonTeamPlayer::find()->where(['id' => $player_id])->one();

        if (!empty($playerInfo)) {
            $avg_vote = \common\models\UserMatchVote::find()->where(['player_id' => $player_id])->average('vote');
            $matchList = \common\models\SeasonMatchPlayer::find()
                ->where(['player_id' => $player_id])->orderBy('id DESC')->all();
            $list = [];
            if (!empty($matchList)) {
                foreach ($matchList as $data) {
                    if ($data->match->is_vote_enabled == 2) {
                        $temp = [
                            'match_id' => $data->match_id,
                            'home_team_id' => $data->match->teamHome->id,
                            'home_away_id' => $data->match->teamAway->id,
                            'match_timestamp' => $data->match->match_timestamp,
                            'match_date' => \Yii::$app->time->asDate($data->match->match_timestamp),
                            'match_time' => \Yii::$app->time->asTime($data->match->match_timestamp),
                            'match_datetime' => \Yii::$app->time->asDatetime($data->match->match_timestamp),
                            'match_ground' => $data->match->match_ground,
                            'goal_of_home_team' => $data->match->goal_of_home_team,
                            'goal_of_away_team' => $data->match->goal_of_away_team,
                            'name_of_home' => $data->match->teamHome->name,
                            'name_of_away' => $data->match->teamAway->name,
                            'logo_of_home' => $data->match->teamHome->logo,
                            'logo_of_away' => $data->match->teamAway->logo,
                            'player_team' => $data->team->name,
                            'player_avg_vote' => \Yii::$app->match->playerAvgVoteInMatch($data->match_id, $data->team_id, $player_id),
                            'position' => !empty($data->position) ? \Yii::$app->player->positionFullForm($data->position) : '-',
                        ];
                        array_push($list, $temp);
                    }
                }
            }
            return ['status' => true, 'data' =>
                [
                    'player_detail' => [
                        'name' => !empty($playerInfo->name) ? $playerInfo->name : '-',
                        'team_logo' => !empty($playerInfo->team->logo) ? $playerInfo->team->logo : '',
                        'team_name' => !empty($playerInfo->team->name) ? $playerInfo->team->name : '',
                        'player_photo' => !empty($playerInfo->photo) ? $playerInfo->photo : '',
                        'average_vote' => !empty($avg_vote) ? number_format($avg_vote, 2) : 0,
                    ],
                    'match_list' => $list,
                ],
            ];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Nessun dato trovato')];
        }
    }

}