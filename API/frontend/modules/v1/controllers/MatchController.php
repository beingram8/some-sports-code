<?php

namespace frontend\modules\v1\controllers;

use common\models\Season;
use common\models\SeasonMatchPlayer;
use common\models\SeasonMatchWinner;
use common\models\UserMatchVote;
use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class MatchController extends ActiveController
{
    public $modelClass = '';

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
                'detail' => ['get'],
                'vote' => ['post'],
                'vote-detail' => ['get'],
                'team-winners' => ['get'],
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
            'matches-for-guest',
            'vote-card',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['detail', 'unlock-match-for-vote', 'get-vote-card-share-url', 'vote', 'vote-detail', 'matches-for-user'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['detail', 'unlock-match-for-vote', 'get-vote-card-share-url', 'vote', 'vote-detail', 'matches-for-user'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionUnlockMatchForVote($match_id)
    {
        $required_token = \Yii::$app->token->getTokenValue('unlock_match_token');
        $user_has_token = \Yii::$app->user->identity->userData->token;
        if ($user_has_token < $required_token) {
            return ['status' => false, 'message' => 'You don\'t have enough Fan Coins. Please buy more Fan Coins and try again '];
        }
        $model = new \common\models\UserTokenTransaction();
        $model->user_id = \Yii::$app->user->id;
        $model->transaction_type = 20;
        $model->token = \Yii::$app->token->getTokenValue('unlock_match_token');
        $model->token_type_id = \Yii::$app->token->getTokenId('unlock_match_token');
        $model->match_id = $match_id;
        $model->remark = "For unlocking a match to vote on: " . $match_id;
        if ($model->save()) {
            \Yii::$app->token->deductUserToken($model->user_id, $required_token);
            return ['status' => true];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Unable to save data.')];
        }

    }
    public function actionMatchesForGuest($for = "current")
    {
        $matches = \common\models\SeasonMatch::find()
            ->where(['OR', ['=', 'is_vote_enabled', 1], ['>', 'match_timestamp', strtotime(date('Y-m-d'))]])
            ->orderBy('match_timestamp ASC')
            ->all();
        $match_list = [];
        $first_match = [];
        if ($matches) {
            foreach ($matches as $k => $match) {
                array_push($match_list, \Yii::$app->match->matchDetailArray($match->id));
            }
        }
        return ['status' => true, 'data' => ['first_match' => $first_match, 'match_list' => $match_list]];
    }

    public function actionMatchesForUser($for = "current")
    {

        $team_id = \Yii::$app->user->identity->userData->team_id;
        $league_id = \Yii::$app->user->identity->userData->league_id;
        
        if ($for == "past") {
            $currentSeason = \Yii::$app->season->getCurrentSeason();
            $login_user_id = \Yii::$app->user->id;
            $matches = \common\models\UserMatchVote::find()->select(['match_id'])
                ->where(['user_id' => $login_user_id])
            //->andWhere(['season' => $currentSeason])
                ->groupBy(['match_id'])
                ->orderBy('id DESC')
                ->all();

            $match_ids = \yii\helpers\ArrayHelper::getColumn($matches, 'match_id');
            $matches = \common\models\SeasonMatch::find()
                ->where(['IN', 'id', $match_ids])
                ->andWhere(['is_vote_enabled' => 2])
                ->orderBy('match_timestamp DESC')
                ->all();
            $match_list = [];
            $first_match = [];
            if ($matches) {
                foreach ($matches as $k => $match) {
                    array_push($match_list, \Yii::$app->match->matchDetailArray($match->id));
                }
            }
        } else {
            if (empty($league_id)) {
                $first_match = \common\models\SeasonMatch::find()
                ->where(['OR', ['=', 'is_vote_enabled', 1], ['>', 'match_timestamp', strtotime(date('Y-m-d'))]])
                ->andWhere(['OR', ['=', 'team_home_id', $team_id], ['=', 'team_away_id', $team_id]])
                ->orderBy("match_timestamp ASC")
                ->one();
                $first_match = !empty($first_match->id) ? \Yii::$app->match->matchDetailArray($first_match->id) : [];

                //We have to get those matches which has vote enable + upcoming matches where not i'm supporting.

                $other_matches = \common\models\SeasonMatch::find()
                    ->where(['OR', ['=', 'is_vote_enabled', 1], ['>', 'match_timestamp', strtotime(date('Y-m-d'))]])
                    ->andWhere(['!=', 'team_home_id', $team_id])
                    ->andWhere(['!=', 'team_away_id', $team_id])
                    ->orderBy('match_timestamp ASC')
                    ->all();
                $match_list = [];
                if ($other_matches) {
                    foreach ($other_matches as $k => $match) {
                        array_push($match_list, \Yii::$app->match->matchDetailArray($match->id));
                    }
                }
            } else {
                $first_match = \common\models\SeasonMatch::find()
                ->where(['OR', ['=', 'is_vote_enabled', 1], ['>', 'match_timestamp', strtotime(date('Y-m-d'))]])
                ->andWhere(['league_id' => $league_id])
                ->orderBy("match_timestamp ASC")
                ->one();
                $first_match = !empty($first_match->id) ? \Yii::$app->match->matchDetailArray($first_match->id) : [];
                //We have to get those matches which has vote enable + upcoming matches where not i'm supporting.

                $other_matches = \common\models\SeasonMatch::find()
                    ->where(['OR', ['=', 'is_vote_enabled', 1], ['>', 'match_timestamp', strtotime(date('Y-m-d'))]])
                    ->andWhere(['league_id' => $league_id])
                    ->orderBy('match_timestamp ASC')
                    ->all();
                $match_list = [];
                if ($other_matches) {
                    foreach ($other_matches as $k => $match) {
                        array_push($match_list, \Yii::$app->match->matchDetailArray($match->id));
                    }
                }
            }
            
        }

        return ['status' => true, 'data' => ['first_match' => $first_match, 'match_list' => $match_list]];
    }
    public function actionDetail($match_id)
    {
        $match = Yii::$app->match->matchDetailArray($match_id);
        if ($match) {
            return ['status' => true, 'message' => 'match detail',
                'data' => [
                    'match' => $match,
                    'teams' => [
                        [
                            'players' => Yii::$app->player->teamPlayer($match_id, $match['home_team_id']),
                            'substitue' => Yii::$app->player->teamSubstitute($match_id, $match['home_team_id']),
                            'coach' => Yii::$app->player->teamCoach($match_id, $match['home_team_id']),
                        ],
                        [
                            'players' => Yii::$app->player->teamPlayer($match_id, $match['home_away_id']),
                            'substitue' => Yii::$app->player->teamSubstitute($match_id, $match['home_away_id']),
                            'coach' => Yii::$app->player->teamCoach($match_id, $match['home_away_id']),
                        ],
                    ],
                ]];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Match object not found.')];
        }
    }

    public function actionVote()
    {

        $postData = Yii::$app->request->post();
        if (isset($postData['UserMatchVote'])) {
            foreach ($postData['UserMatchVote'] as $value) {
                $model = \common\models\UserMatchVote::find()->where(['match_id' => $value['match_id'],
                    'team_id' => $value['team_id'], 'player_id' => $value['player_id'], 'user_id' => Yii::$app->user->identity->id])
                    ->one();
                if (empty($model)) {
                    $model = new UserMatchVote();
                    $model->season = \Yii::$app->season->getCurrentSeason();
                    $model->match_id = $value['match_id'];
                    $model->team_id = $value['team_id'];
                    $model->player_id = $value['player_id'];
                    $model->user_id = Yii::$app->user->identity->id;
                    $model->vote = $value['vote'];
                    if (!$model->save()) {
                        return ['status' => false, 'message' => \Yii::$app->general->error($model->errors)];
                    }
                }
            }
            \Yii::$app->token->updateUserToken(
                \Yii::$app->user->identity->id, \Yii::$app->token->getTokenValue('submit_rate_card'));

            $assign_user_token = new \common\models\UserTokenTransaction();
            $assign_user_token->user_id = Yii::$app->user->identity->id;
            $assign_user_token->transaction_type = 10;
            $assign_user_token->token_type_id = \Yii::$app->token->getTokenId('submit_rate_card');
            $assign_user_token->token = \Yii::$app->token->getTokenValue('submit_rate_card');
            $assign_user_token->created_by = Yii::$app->user->identity->id;
            $assign_user_token->remark = 'For making the report card of ' . Yii::$app->match->match_name($value['match_id']);
            if ($assign_user_token->save()) {
                return ['status' => true, 'data' => [
                    'vote_details' => $postData['UserMatchVote'],
                    'is_animation' => true,
                ],
                ];
            }
        }
        return ['status' => false];
    }

    public static function voteCard($match_id, $team_id, $login_user_id)
    {
        $model = UserMatchVote::find()
            ->where(['match_id' => $match_id, 'team_id' => $team_id, 'user_id' => $login_user_id])
            ->all();
        if (isset($model)) {
            $playerWithVote = \Yii::$app->match->playerWithVote($match_id, $login_user_id);
            $player_id = \yii\helpers\ArrayHelper::getColumn($model, 'player_id');

            $playerQuery = SeasonMatchPlayer::find()
                ->join('INNER JOIN', 'season_team_player', 'season_team_player.id = season_match_players.player_id')
                ->where(['in', 'player_id', $player_id])
                ->andWhere(['match_id' => $match_id, 'season_match_players.team_id' => $team_id])
                ->all();

            $playerArray = [];
            $substituteArray = [];
            $coachArray = [];
            $pointArray = [];
            $avgVoteArray = [];
            $point_calculated = \common\models\SeasonMatch::find()->where(['id' => $match_id, 'is_point_calculated' => 1])->count();
            if ($point_calculated) {
                $avgVoteArray = \Yii::$app->match->playersWithAvgVoteInMatch($match_id, $team_id);
                $avgVoteArray = \yii\helpers\ArrayHelper::map($avgVoteArray, 'player_id', 'vote');

                $pointArray = \Yii::$app->match->playersWithPointInMatch($match_id, $team_id, $login_user_id);
            }
            foreach ($playerQuery as $player) {
                if ($player['type'] == 1) {
                    $tempPlayer = [
                        'player_id' => $player->player->id,
                        'name' => $player->player->name,
                        'photo' => $player->player->photo,
                        'vote' => isset($playerWithVote[$player->player->id]) ? $playerWithVote[$player->player->id] : "",
                        'avg_vote' => isset($avgVoteArray[$player->player->id]) ? number_format($avgVoteArray[$player->player->id], 1) : "",
                        'point' => isset($pointArray[$player->player->id]) ? $pointArray[$player->player->id] : "",
                        'position' => \Yii::$app->player->positionFullForm($player->position),
                    ];
                    array_push($playerArray, $tempPlayer);
                }
                if ($player['type'] == 2) {
                    $tempSubstitute = [
                        'player_id' => $player->player->id,
                        'name' => $player->player->name,
                        'photo' => $player->player->photo,
                        'vote' => isset($playerWithVote[$player->player->id]) ? $playerWithVote[$player->player->id] : "",
                        'avg_vote' => isset($avgVoteArray[$player->player->id]) ? number_format($avgVoteArray[$player->player->id], 1) : "",
                        'point' => isset($pointArray[$player->player->id]) ? $pointArray[$player->player->id] : "",
                        'position' => \Yii::$app->player->positionFullForm($player->position),
                    ];
                    array_push($substituteArray, $tempSubstitute);
                }
                if ($player['type'] == 3) {
                    $tempCoach = [
                        'player_id' => $player->player->id,
                        'name' => $player->player->name,
                        'photo' => $player->player->photo,
                        'vote' => isset($playerWithVote[$player->player->id]) ? $playerWithVote[$player->player->id] : "",
                        'avg_vote' => isset($avgVoteArray[$player->player->id]) ? number_format($avgVoteArray[$player->player->id], 1) : "",
                        'point' => isset($pointArray[$player->player->id]) ? $pointArray[$player->player->id] : "",
                        'position' => \Yii::$app->player->positionFullForm($player->position),
                    ];
                    array_push($coachArray, $tempCoach);
                }
            }

            return [
                'match' => Yii::$app->match->matchDetailArray($match_id),
                'players' => $playerArray,
                'substitue' => $substituteArray,
                'coach' => $coachArray,
            ];
        }
        return ['status' => false];
    }
    public function actionVoteDetail($match_id)
    {
        $match = Yii::$app->match->matchDetailArray($match_id);
        if ($match) {
            return [
                'status' => true,
                'data' => [
                    'match' => $match,
                    'teams' => [
                        $this->voteCard($match_id, $match['home_team_id'], \Yii::$app->user->identity->id),
                        $this->voteCard($match_id, $match['home_away_id'], \Yii::$app->user->identity->id),
                    ],
                ],
            ];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Object not found.')];
        }
    }
    public function actionVoteCard($token)
    {
        $tokens = explode("-", $token);
        $match_id = isset($tokens[0]) ? $tokens[0] : "";
        $auth_key = isset($tokens[1]) ? $tokens[1] : "";
        $new_auth = substr($token, strpos($token, "-") + 1);
        $user = \common\models\User::find()->where(['auth_key' => $new_auth])->one();
        $vote_by_user_id = "";
        if ($user) {
            $vote_by_user_id = $user->id;
        }
        $match = Yii::$app->match->matchDetailArray($match_id);
        if ($match) {
            return ['status' => true, 'data' => [
                'match' => $match,
                'teams' => [
                    $this->voteCard($match_id, $match['home_team_id'], $vote_by_user_id),
                    $this->voteCard($match_id, $match['home_away_id'], $vote_by_user_id),
                ],
            ],
            ];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Object not found.')];
        }
    }
    public function actionGetVoteCardShareUrl($match_id)
    {
        $login_user_id = \Yii::$app->user->id;
        $login_user_auth_key = \Yii::$app->user->identity->auth_key;
        $match = \common\models\UserMatchVote::find()
            ->select(['match_id'])
            ->where(['user_id' => $login_user_id])
            ->andWhere(['match_id' => $match_id])
            ->one();
        if ($match) {
            $sharable_url = \Yii::$app->params['frontend_url'] . '/shareable-vote-card?' . $match_id . '-' . $login_user_auth_key;
            return ['status' => true, 'data' => ['sharable_url' => $sharable_url]];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'It is look like you have not submitted a vote for this match.')];
        }
    }

    public function actionTeamWinners($match_id, $team_id)
    {
        if (!empty($match_id) && !empty($team_id)) {
            $winners = SeasonMatchWinner::find()
                ->where(['season_match_winner.match_id' => $match_id])
                ->andWhere(['season_match_winner.team_id' => $team_id])
                ->orderBy('season_match_winner.points DESC')
                ->limit(10)
                ->all();

            $data = [];
            $i = 0;
            foreach ($winners as $key => $value) {
                $data[$key]['name'] = $value['user']['first_name'] . ' ' . $value['user']['last_name'];
                $data[$key]['user_photo'] = Yii::$app->userData->photo($value['user_id']);
                $data[$key]['points'] = $value['points'];
                $data[$key]['user_team_name'] = !empty($value->team) ? $value->team->name : '';
                $data[$key]['team_photo'] = !empty($value->team->logo) ? $value->team->logo : '';
                $data[$key]['team'] = \Yii::$app->userData->getUserTeam($value['user_id']);
                $data[$key]['rank'] = $i + 1; //last rank logic here
                $i++;
            }
            return ['status' => true, 'data' => $data];
        }
        return ['status' => false, 'message' => \Yii::t('app', 'Missing parameter value')];
    }
}
