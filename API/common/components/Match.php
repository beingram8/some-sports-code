<?php

namespace common\components;

use Yii;

class Match extends \yii\base\Component
{

    public function match_name($match_id)
    {

        $match = \common\models\SeasonMatch::find()->where(['id' => $match_id])->one();
        if ($match) {
            return $match->teamHome->name . ' V/s ' . $match->teamAway->name;
        }
        return '';
    }

    public function matchTeams($match_id = 0)
    {
        if ($match_id == 0) {
            return \Yii::$app->team->getTeams();
        } else {
            $match = \common\models\SeasonMatch::find()->where(['id' => $match_id])->one();
            if ($match) {
                $teams = array($match->teamHome->id => $match->teamHome->name, $match->teamAway->id => $match->teamAway->name);
                return $teams;
            }
        }
        return [];
    }
    public function loginUserVotedMatch($match_id)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        } else {
            $data = \common\models\UserMatchVote::find()
                ->where(['match_id' => $match_id,
                    'user_id' => \Yii::$app->user->id])
                ->count();
            return $data ? true : false;
        }
    }
    public function votedMatch($match_id, $team_id, $login_user_id)
    {
        return \common\models\UserMatchVote::find()
            ->where(['user_id' => $login_user_id])
            ->andWhere(['team_id' => $team_id, 'match_id' => $match_id])
            ->asArray()
            ->count();
    }
    public function today_voted_match()
    {
        $login_user_id = Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id;
        $todays_vote_matches = \common\models\UserMatchVote::find()
            ->select(['match_id'])
            ->join('INNER JOIN', 'season_match', 'season_match.id=user_match_vote.match_id')
            ->where(['user_match_vote.user_id' => $login_user_id])
            ->andWhere('DATE(created_at) = ' . date('Y-m-d'))
            ->groupBy('match_id')
            ->asArray()
            ->all();
        return \yii\helpers\ArrayHelper::getColumn($todays_vote_matches, 'match_id');
    }
    public function levelBasedVotingEnable($match_day)
    {
        $login_user_id = Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id;
        $no_match_allowed_in_day = Yii::$app->user->isGuest ? 0 : \Yii::$app->user->identity->userData->level->no_match_for_vote;

        $todays_vote_matches = \common\models\UserMatchVote::find()
            ->join('INNER JOIN', 'season_match', 'season_match.id = user_match_vote.match_id')
            ->where(['user_match_vote.user_id' => $login_user_id])
            ->andWhere(['season_match.match_day' => $match_day])
            ->groupBy('user_match_vote.match_id')
            ->count();

        if ($no_match_allowed_in_day > $todays_vote_matches) {
            return true;
        } else {
            return false;
        }
    }
    public function isUnlockedMatch($match_id)
    {
        $login_user_id = Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id;
        return \common\models\UserTokenTransaction::find()
            ->join("INNER JOIN", 'token_type', 'token_type.id=user_token_transaction.token_type_id')
            ->where(['match_id' => $match_id, 'user_id' => $login_user_id, 'token_type.name' => 'unlock_match_token'])
            ->count();
    }


    public function matchDetailArray($match_id)
    {
        $matchDetail = \common\models\SeasonMatch::find()
            ->where(['id' => $match_id])
            ->one();
        $login_user_id = Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id;

        if (isset($matchDetail)) {
            $league_name = !empty($matchDetail->league) ? $matchDetail->league->name : "";

            if ($matchDetail->is_vote_enabled == 1) { // Vote Enable
                $is_vote_enabled = true;
            } else if ($matchDetail->is_vote_enabled == 0) { // vote pending
                $is_vote_enabled = false;
            } else { //vote close
                $is_vote_enabled = false;
            }
            $is_already_voted = $this->loginUserVotedMatch($matchDetail->id);
            
            $lock = true;
            if ($is_vote_enabled) {
                if ($this->levelBasedVotingEnable($matchDetail->match_day)) {
                    $lock = false;
                } else {
                    if ($this->isUnlockedMatch($matchDetail->id)) {
                        $lock = false;
                    }
                }
            }
           
            return [
                'match_id' => $matchDetail->id,
                'unlock_token' => \Yii::$app->token->getTokenValue('unlock_match_token'),
                'home_team_id' => $matchDetail->teamHome->id,
                'home_away_id' => $matchDetail->teamAway->id,
                'match_timestamp' => $matchDetail->match_timestamp,
                'match_date' => \Yii::$app->time->asDate($matchDetail->match_timestamp),
                'match_time' => \Yii::$app->time->asTime($matchDetail->match_timestamp),
                'match_datetime' => \Yii::$app->time->asDatetime($matchDetail->match_timestamp),
                'match_ground' => $matchDetail->match_ground,
                'goal_of_home_team' => $matchDetail->goal_of_home_team,
                'goal_of_away_team' => $matchDetail->goal_of_away_team,
                'vote_closing_at' => $matchDetail->vote_closing_at,
                'is_vote_enabled' => $is_vote_enabled,
                'lock' => $lock,
                'is_already_voted' => $is_already_voted,
                'name_of_home' => $matchDetail->teamHome->name,
                'name_of_away' => $matchDetail->teamAway->name,
                'logo_of_home' => $matchDetail->teamHome->logo,
                'logo_of_away' => $matchDetail->teamAway->logo,
                'league_id' => $matchDetail->league_id,
                'league_name' => $league_name,
                'match_url' => 'https://prf.hn/click/camref:1100lf8kP', //$matchDetail->match_url ? $matchDetail->match_url : false,
                'match_ground_url' => $matchDetail->match_url ? $matchDetail->match_url : 'https://www.footballticketnet.it/italian-serie-a',
                'home_total_point' => \Yii::$app->team->getVoteTeamPoint($matchDetail->id, $matchDetail->teamHome->id),
                'away_total_point' => \Yii::$app->team->getVoteTeamPoint($matchDetail->id, $matchDetail->teamAway->id),
            ];
        }
        return [];
    }
    public function getMatchPlayer($match_id, $team_id, $type)
    {
        return \common\models\SeasonMatchPlayer::find()
            ->where(['match_id' => $match_id, 'team_id' => $team_id, 'type' => $type])
            ->all();
    }
    public function idFromApiId($api_match_id)
    {
        $match = \common\models\SeasonMatch::find()->where(['api_match_id' => $api_match_id])->one();
        if ($match) {
            return $match->id;
        }
        return \Yii::$app->general->throwError('Please import match first.');
    }
    public function getMatchesBySeason($season)
    {
        $matches = \common\models\SeasonMatch::find()->where(['season' => $season])->asArray()->all();
        return \yii\helpers\ArrayHelper::getColumn($matches, 'api_match_id');
    }
    public function setSeason($fixture)
    {
        if (!empty($fixture['league']) && !empty($fixture['league']['season'])) {
            $season = $fixture['league']['season'];
            $season_from_db = \Yii::$app->season->getSeason($season);
            if ($season_from_db) {
                return $season_from_db->season;
            } else {
                return false;
            }
        }
        return false;
    }
    public function setLeague($fixture)
    {
        if (!empty($fixture['league']) && !empty($fixture['league']['id'])) {
            $api_league_id = $fixture['league']['id'];
            $league_from_db = \Yii::$app->league->getLeagueByApiId($api_league_id);
            if ($league_from_db) {
                return $league_from_db->id;
            } else {
                return false;
            }
        }
        return false;
    }
    public function setHomeTeam($fixture)
    {
        if (!empty($fixture['teams']) && !empty($fixture['teams']['home'])) {
            $team = $fixture['teams']['home'];
            $api_team_id = $team['id'];
            $team_from_db = \Yii::$app->team->getTeamByApiId($api_team_id);
            if ($team_from_db) {
                return $team_from_db->id;
            } else {
                return false;
            }
        }
        return false;
    }
    public function setAwayTeam($fixture)
    {
        if (!empty($fixture['teams']) && !empty($fixture['teams']['away'])) {
            $team = $fixture['teams']['away'];
            $api_team_id = $team['id'];
            $team_from_db = \Yii::$app->team->getTeamByApiId($api_team_id);
            if ($team_from_db) {
                return $team_from_db->id;
            } else {
                return false;
            }
        }
        \Yii::$app->general->throwError('Invalid teams object.');
    }
    public function setWinner($fixture)
    {
        if (!empty($fixture['teams'])) {
            if ($fixture['teams']['home']['winner']) {
                $winner_team_id = $fixture['teams']['home']['id'];
                $team_from_db = \Yii::$app->team->getTeamByApiId($winner_team_id);
                if ($team_from_db) {
                    return $team_from_db->id;
                }
            } else if ($fixture['teams']['away']['winner']) {
                $winner_team_id = $fixture['teams']['away']['id'];
                $team_from_db = \Yii::$app->team->getTeamByApiId($winner_team_id);
                if ($team_from_db) {
                    return $team_from_db->id;
                }
            }
            return false;
        }
        return false;
    }
    public function setVotClosingTime($match_timestamp)
    {
        $time = (24 * 60 * 60); // 26 hour after match
        return $match_timestamp + $time;
    }
    public function setStatus($status)
    {
        if ($status == "TBD") { //Time To Be Defined
            return 0;
        } else if ($status == "NS") { // Not Started
            return 0;
        } else if ($status == "1H") { //First Half, Kick Off
            return 0;
        } else if ($status == "HT") { //Halftime
            return 0;
        } else if ($status == "2H") { //Second Half, 2nd Half Started
            return 0;
        } else if ($status == "ET") { //Extra Time
            return 0;
        } else if ($status == "P") { //Penalty In Progress
            return 0;
        } else if ($status == "AET") { //Match Finished After Extra Time
            return 1;
        } else if ($status == "PEN") { //Match Finished After Penalty
            return 1;
        } else if ($status == "BT") { //Break Time (in Extra Time)
            return 0;
        } else if ($status == "SUSP") { //Match Suspended
            return -1;
        } else if ($status == "INT") { //Match Interrupted
            return -1;
        } else if ($status == "PST") { //Match Postponed
            return -1;
        } else if ($status == "CANC") { // Match Cancelled
            return -1;
        } else if ($status == "ABD") { //Match Abandoned
            return -1;
        } else if ($status == "AWD") { //Technical Loss
            return -1;
        } else if ($status == "WO") { //WalkOver
            return -1;
        } else if ($status == "FT") { //Match Finished
            return 1;
        }
    }
    public function fetchRemainingSubstitute($match_id = 0)
    {
        if ($match_id) {
            $matches = \common\models\SeasonMatch::find()
                ->andWhere(['IN', 'id', [$match_id]])
                ->all();
        } else {
            $matches = \common\models\SeasonMatch::find()
                ->andWhere(['season_match.is_vote_enabled' => 1])
                ->all();
        }
        if ($matches) {
            foreach ($matches as $match) {
                $match_subsitute = \common\models\SeasonMatchPlayer::find()->where(['match_id' => $match->id, 'type' => 2, 'team_id' => $match->team_home_id])
                    ->one();
                $match_subsitute_2 = \common\models\SeasonMatchPlayer::find()->where(['match_id' => $match->id, 'type' => 2, 'team_id' => $match->team_away_id])
                    ->one();

                if (empty($match_subsitute) || empty($match_subsitute_2)) {
                    \Yii::$app->match->setPlayerAfterMatch($match->api_match_id);
                }
            }
        }
    }

    public function setTeamPlayer($player, $season, $team_id, $type)
    {
        $tempPlayer = !empty($player['number']) ? $player['number'] : '';
        $player_api_id = !empty($player['id']) ? $player['id'] : "";
        if ($type == 3) { // coach
            $coachInfo = \Yii::$app->fetch->fetchCoach($player_api_id, $season);
            if ($coachInfo && $coachInfo['status']) {
                $coach = $coachInfo['data'][0];
                $playerModel = \common\models\SeasonTeamPlayer::find()->where(['api_player_id' => $coach['id']])->one();
                $playerModel = !empty($playerModel) ? $playerModel : new \common\models\SeasonTeamPlayer;
                $playerModel->team_id = $team_id;
                $playerModel->photo = $coach['photo'];
                $playerModel->name = $coach['name'];
                $playerModel->api_player_id = $coach['id'];
                $playerModel->type = 3;
                $playerModel->api_response = json_encode($coachInfo['data'][0]);
                if ($playerModel->save()) {
                    return $playerModel->id;
                }
                \Yii::$app->general->throwError(json_encode($playerModel->errors));
            } else {
                $playerModel = \common\models\SeasonTeamPlayer::find()
                    ->where(['name' => $player['name'], 'team_id' => $team_id])
                    ->one();
                $playerModel = !empty($playerModel) ? $playerModel : new \common\models\SeasonTeamPlayer;
            }
        } else {
            $playerModel = \common\models\SeasonTeamPlayer::find()->where(['api_player_id' => $player_api_id, 'type'=>1])
                                    ->one();

            if(empty($playerModel)) {   
                $playerInfo = !empty($player_api_id) ? \Yii::$app->fetch->fetchPlayer($player_api_id, $season) : "";
                if ($playerInfo && $playerInfo['status']) {
                    if (isset($playerInfo['data'][0]['player'])) {
                        $player = $playerInfo['data'][0]['player'];
                        $player['number'] = $tempPlayer;
                        
                        $playerModel = \common\models\SeasonTeamPlayer::find()->where(['api_player_id' => $player['id'], 'type'=>1])
                                        ->one();
                        $playerModel = !empty($playerModel) ? $playerModel : new \common\models\SeasonTeamPlayer;
                        $playerModel->api_response = json_encode($playerInfo['data'][0]);
                    } else {
                        $playerModel = \common\models\SeasonTeamPlayer::find()
                            ->where(['name' => $player['name'], 'team_id' => $team_id])
                            ->one();
                        $playerModel = !empty($playerModel) ? $playerModel : new \common\models\SeasonTeamPlayer;
                    }
                } else {
                    $playerModel = \common\models\SeasonTeamPlayer::find()
                        ->where(['name' => $player['name'], 'team_id' => $team_id])
                        ->one();
                    $playerModel = !empty($playerModel) ? $playerModel : new \common\models\SeasonTeamPlayer;
                }
                try {
                    $playerModel->team_id = $team_id;
                    $playerModel->photo = $player['photo'];
                    $playerModel->name = $player['name'];
                    $playerModel->number = !empty($player['number']) ? $player['number'] : "";
                    $playerModel->api_player_id = $player['id'];
                    $playerModel->type = 1;
                    $playerModel->api_response = null;
                    if ($playerModel->save()) {
                        return $playerModel->id;
                    } else {
                        \Yii::$app->general->throwError(json_encode($playerModel->errors));
                    }
                } catch (\Exception $e) {
                    print_r($player);die;
                }
            } else {
                $playerModel->team_id = $team_id;
                $playerModel->number = !empty($player['number']) ? $player['number'] : "";
                $playerModel->api_player_id = $player['id'];
                $playerModel->type = 1;
                if ($playerModel->save()) {
                    return $playerModel->id;
                } else {
                    \Yii::$app->general->throwError(json_encode($playerModel->errors));
                }
            }
        }
    }


    public function setMatchPlayer($player, $fixture_id, $season, $team_from_db, $type)
    {
        $player = $player['player'];
        $player_id = \Yii::$app->match->setTeamPlayer($player, $season, $team_from_db->id, $type);
        if ($player_id) {
            $matchPlayer = \common\models\SeasonMatchPlayer::find()
                ->where(['player_id' => $player_id,
                    'api_fixture_id' => $fixture_id,
                    'api_team_id' => $team_from_db->api_team_id])->one();
            $matchPlayer = !empty($matchPlayer) ? $matchPlayer : new \common\models\SeasonMatchPlayer;
            $matchPlayer->api_fixture_id = $fixture_id;
            $matchPlayer->api_player_id = $player['id'];
            $matchPlayer->api_team_id = $team_from_db->api_team_id;
            $matchPlayer->player_id = $player_id;
            $matchPlayer->type = $type;
            $matchPlayer->position = !empty($player['pos']) ? $player['pos'] : "";

            $matchPlayer->match_id = \Yii::$app->match->idFromApiId($matchPlayer->api_fixture_id);
            $matchPlayer->team_id = \Yii::$app->team->idFromApiId($matchPlayer->api_team_id);

            if (!$matchPlayer->save()) {
                \Yii::$app->general->throwError(json_encode($matchPlayer->errors));
            }
        } else {

            \Yii::$app->general->throwError('player_id not fetching.');
        }
    }


    public function setPlayerAfterMatch($fixture_id)
    {
        $match = \common\models\SeasonMatch::find()->where(['api_match_id' => $fixture_id])->one();

        if ($match) {
            $lineups = \Yii::$app->fetch->fetchLineupForFixture($match->api_match_id);
            $apiLogResponse = new \common\models\ApiResponseLog;
            $content = json_encode($lineups);
            $apiLogResponse->content = $content;
            $apiLogResponse->match_id = $match->id;
            $apiLogResponse->api_match_id = $fixture_id;
            $apiLogResponse->is_main = true;
            $apiLogResponse->created_at = date('Y-m-d H:i:s');
            $apiLogResponse->save();
            $season = $match->season;
            if ($lineups['status']) {
                if ($lineups['data']) {
                    foreach ($lineups['data'] as $team) {
                        $teamData = $team['team'];
                        $team_from_db = \Yii::$app->team->getTeambyApiId($teamData['id']);
                        if ($team_from_db) {
                            // Start  11
                            if (!empty($team['startXI'])) {
                                $type = "1";
                                
                                foreach ($team['startXI'] as $player) {
                                    $player['player']['photo'] = $teamData['logo'];
                                    $player['player']['number'] = !empty($player['player']['number']) ? $player['player']['number'] : '';
                                    \Yii::$app->match->setMatchPlayer($player, $match->api_match_id, $season, $team_from_db, $type);
                                }
                            }
                            // echo "<br>";
                            // echo json_encode($team['substitutes']);
                            // exit();
                            // Save Substitutes
                            // if(!empty($team["substitutes"])) {
                            //     $type = "2";
                            //     $player = [];
                            //     foreach($team["substitutes"] as $player) {
                            //         $player['player']['photo'] = $teamData['logo'];
                            //         $player['player']['number'] = !empty($player['player']['number']) ? $player['player']['number'] : '';
                            //         \Yii::$app->match->setMatchPlayer($player, $match->api_match_id, $season, $team_from_db, $type);
                            //     }
                            // }
                            //substitutes
                            $substitutes = \Yii::$app->fetch->fetchSubstitutes($match->api_match_id, $teamData['id']);
                            $apiLogResponse = new \common\models\ApiResponseLog;
                            $content = json_encode($substitutes);
                            $apiLogResponse->content = $content;
                            $apiLogResponse->match_id =$match->id;
                            $apiLogResponse->api_match_id = $fixture_id;
                            $apiLogResponse->is_main = false;
                            $apiLogResponse->created_at = date('Y-m-d H:i:s');
                            $apiLogResponse->save();
                            if ($substitutes['status']) {
                                $player = [];
                                foreach ($substitutes['data'] as $player) {
                                    if($player['type'] == "subst") {
                                        if ($player['type'] == "subst" && !empty($player['player'])) {
                                            $existPlayer = \common\models\SeasonMatchPlayer::find()
                                                ->where(['match_id' => $match->id, 'api_player_id' => $player['player']["id"], 'type' => 1])
                                                ->one();
                                            if(empty($existPlayer))
                                            {
                                                $type = "2";
                                                $player['player']['photo'] = $teamData['logo'];
                                                \Yii::$app->match->setMatchPlayer(['player' => $player['player']], $match->api_match_id, $season, $team_from_db, $type);
                                            }
                                            
                                        } 
                                        if ($player['type'] == "subst" && !empty($player['assist'])) {
                                            $existPlayer = \common\models\SeasonMatchPlayer::find()
                                                ->where(['match_id' => $match->id, 'api_player_id' => $player['assist']["id"], 'type' => 1])
                                                ->one();
                                            if(empty($existPlayer))
                                            {
                                                $type = "2";
                                                $player = $player["assist"];
                                                $player['photo'] = $teamData['logo'];
                                                \Yii::$app->match->setMatchPlayer(['player' => $player], $match->api_match_id, $season, $team_from_db, $type);
                                            }
                                        }
                                    }
                                }
                            }

                            // coach
                            if (!empty($team['coach']['id'])) {
                                $team['coach']['pos'] = "Coach";
                                $team['coach']['photo'] = $teamData['logo'];
                                $type = "3";
                                \Yii::$app->match->setMatchPlayer(['player' => $team['coach']], $match->api_match_id, $season, $team_from_db, $type);
                            }
                        }
                    }
                    return ['status' => true];
                } else {
                    return ['status' => false, 'message' => 'There is no data to fetch may be match is not finished yet.'];
                }
            } else {
                return ['status' => false, 'message' => $lineups['message']];
            }
        } else {
            return ['status' => false, 'message' => 'There are no matches.'];
        }
    }
    public function updateFixture($fixture_id)
    {
        $fixtureResp = Yii::$app->fetch->fetchFixture($fixture_id);
        if ($fixtureResp['status']) {
            $fixture = $fixtureResp['data'][0];

            $seasonMatch = \common\models\SeasonMatch::find()->where(['api_match_id' => $fixture_id])->one();
            $seasonMatch = !empty($seasonMatch) ? $seasonMatch : new \common\models\SeasonMatch;

            $team_home_id = \Yii::$app->match->setHomeTeam($fixture);
            $team_away_id = \Yii::$app->match->setAwayTeam($fixture);
            $league_id = \Yii::$app->match->setLeague($fixture);
            $season = \Yii::$app->match->setSeason($fixture);

            if (empty($season)) {
                $res = ['status' => false, 'message' => 'Please import season first'];
            } else if (empty($league_id)) {
                $res = ['status' => false, 'message' => 'Please import league first'];
            } else if (empty($team_home_id)) {
                $res = ['status' => false, 'message' => 'Please import team first - team_home_id'];
            } else if (empty($team_away_id)) {
                $res = ['status' => false, 'message' => 'Please import team first - team_away_id'];
            } else {
                $seasonMatch->season = $season;
                $seasonMatch->league_id = $league_id;
                $seasonMatch->match_timestamp = $fixture['fixture']['timestamp'];
                $seasonMatch->match_date = date("Y-m-d", $fixture['fixture']['timestamp']);
                $seasonMatch->team_home_id = $team_home_id;
                $seasonMatch->team_away_id = $team_away_id;
                $seasonMatch->match_ground = $fixture['fixture']['venue']['name'];
                $seasonMatch->match_city = $fixture['fixture']['venue']['city'];
                $seasonMatch->goal_of_home_team = $fixture['goals']['home'];
                $seasonMatch->goal_of_away_team = $fixture['goals']['away'];
                $seasonMatch->api_match_id = $fixture_id;
                $seasonMatch->api_response = json_encode($fixture);
                $seasonMatch->is_match_finished = \Yii::$app->match->setStatus($fixture['fixture']['status']['short']);
                if ($seasonMatch->save()) {
                    // Match Finished

                    if ($seasonMatch->is_match_finished == 1 && $seasonMatch->is_vote_enabled == 0) { // Finished
                        $player_response = \Yii::$app->match->setPlayerAfterMatch($seasonMatch->api_match_id);
                        if (!empty($player_response['status'])) {
                            $seasonMatch->winner_team_id = \Yii::$app->match->setWinner($fixture);
                            $seasonMatch->is_vote_enabled = 1; //Vote Enabled
                            $seasonMatch->vote_closing_at = \Yii::$app->match->setVotClosingTime($seasonMatch->match_timestamp);
                        } else {
                            \Yii::info($player_response, 'PlayerDataNotSet');
                        }
                        // Match Canceled
                    } else if ($seasonMatch->is_match_finished == -1) {
                        $seasonMatch->is_vote_enabled = 2; // Vote Closed
                    }
                    if (!$seasonMatch->save()) {
                        \Yii::$app->general->throwError($seasonMatch->errors);
                    }
                    if ($seasonMatch->is_vote_enabled == 1) {
                        $home_team_name = $seasonMatch->teamHome->name;
                        $away_team_name = $seasonMatch->teamAway->name;
                        \Yii::$app->notification->savePush(
                            'Now you can make the report card of ' . $home_team_name . '-' . $away_team_name,
                            'for the match ' . $home_team_name . ' V/s ' . $away_team_name,
                            'vote',
                            ['match_id' => $seasonMatch->id, 'type' => 'vote_open'],
                            \Yii::$app->security->generateRandomString(12),
                            $per_page = 1000
                        );
                    }
                    $res = ['status' => true];
                } else {
                    $res = ['status' => false, 'message' => json_encode($seasonMatch->errors)];
                }
            }
        } else {
            $res = $fixtureResp;
        }
        return $res;
    }
    public function playerPointInMatch($match_id, $team_id, $user_id, $player_id)
    {
        $players = \common\models\UserPointTransaction::find()
            ->where([
                'transaction_type' => 1,
                'match_id' => $match_id,
                'team_id' => $team_id,
                'player_id' => $player_id,
                'user_id' => $user_id])
            ->asArray()->one();
        return $players ? $players['points'] : "";
    }
    public function playerAvgVoteInMatch($match_id, $team_id, $player_id)
    {
        $players = \common\models\UserMatchVote::find()
            ->select(['sum(vote)  / COUNT(user_id) as vote'])
            ->where(['match_id' => $match_id, 'team_id' => $team_id, 'player_id' => $player_id])
            ->one();
        return $players ? number_format($players['vote'], 2) : "";
    }
    public function playerWithVote($match_id, $login_user_id)
    {
        $models = \common\models\UserMatchVote::find()
            ->where(['match_id' => $match_id, 'user_id' => $login_user_id])
            ->all();
        return \yii\helpers\ArrayHelper::map($models, 'player_id', 'vote');
    }
    public function playersWithPointInMatch($match_id, $team_id, $login_user_id)
    {
        $players = \common\models\UserPointTransaction::find()
            ->where([
                'transaction_type' => 1,
                'match_id' => $match_id,
                'team_id' => $team_id,
                'user_id' => $login_user_id])
            ->asArray()
            ->all();
        $pointArray = \yii\helpers\ArrayHelper::map($players, 'player_id', 'points');
        return $pointArray;
    }
    public function playersWithAvgVoteInMatch($match_id, $team_id)
    {
        $players = \common\models\UserMatchVote::find()
            ->select(['player_id, sum(vote)  / COUNT(user_id) as vote'])
            ->where(['match_id' => $match_id, 'team_id' => $team_id])
            ->groupBy('player_id')
            ->all();
        return $players;
    }
    public function _get_user_all_point($player_id, $avg_vote, $near_vote, $match_id, $team_id)
    {
        $data = \common\models\UserMatchVote::find()
            ->select(['user_id', 'season', 'player_id'])
            ->where(['match_id' => $match_id, 'team_id' => $team_id, 'player_id' => $player_id])
            ->andWhere(['abs(vote-' . $avg_vote . ')' => $near_vote])
            ->asArray()
            ->all();
        return $data;
    }
    public function closest_value_to_avg_vote($player_id, $avg_vote, $match_id, $team_id)
    {
        $data = \common\models\UserMatchVote::find()
            ->select(['player_id,min(abs(vote-' . $avg_vote . ')) as differentVote'])
            ->where(['match_id' => $match_id, 'team_id' => $team_id, 'player_id' => $player_id])
            ->orderBy('differentVote ASC')
            ->asArray()
            ->one();
        return $data && isset($data['differentVote']) ? $data['differentVote'] : 0;
    }
    public function _calculatePoint($match_id, $team_id)
    {

        $players_avg_vote = \Yii::$app->match->playersWithAvgVoteInMatch($match_id, $team_id);

        if ($players_avg_vote) {

            foreach ($players_avg_vote as $player_avg_vote) { // match of team of players loop

                $min_user_point = \Yii::$app->match->closest_value_to_avg_vote($player_avg_vote['player_id'], $player_avg_vote['vote'], $match_id, $team_id);

                $all_user_point = \Yii::$app->match->_get_user_all_point(
                    $player_avg_vote['player_id'],
                    $player_avg_vote['vote'],
                    $min_user_point,
                    $match_id,
                    $team_id
                );

                if ($all_user_point) {
                    $tokens = 0;
                    foreach ($all_user_point as $user_point) { //all player point for user
                        $model = \common\models\UserPointTransaction::find()
                            ->where([
                                'user_id' => $user_point['user_id'],
                                'player_id' => $user_point['player_id'],
                                'match_id' => $match_id,
                                'team_id' => $team_id,
                            ])->one();
                        $model = $model ? $model : new \common\models\UserPointTransaction;
                        $model->user_id = $user_point['user_id'];
                        $model->points = 1;
                        $model->player_id = $user_point['player_id'];
                        $model->match_id = $match_id;
                        $model->team_id = $team_id;
                        $model->type = 1;
                        $model->transaction_type = 1;
                        if ($model->save()) {
                            \Yii::$app->userData->sum_of_point($model->user_id);
                            $model2 = new \common\models\UserTokenTransaction();
                            $model2->user_id = $user_point['user_id'];
                            $model2->transaction_type = 10;
                            $model2->token = \Yii::$app->token->getTokenValue('correct_vote');
                            $model2->token_type_id = \Yii::$app->token->getTokenId('correct_vote');
                            $model2->remark = 'For correctly voting in ' . $this->match_name($match_id);
                            $model2->created_by = $user_point['user_id'];
                            if ($model2->save(false)) {
                                \Yii::$app->token->updateUserToken($model2->user_id, $model2->token);
                            }
                        } else {
                            \Yii::info($model->errors, 'calculatePoint');
                        }
                    }

                }
            }
            \Yii::$app->match->_winnerProcess($match_id, $team_id);
        }
    }
    public function _winnerProcess($match_id, $team_id)
    {
        $userPointOfMatch = \common\models\UserPointTransaction::find()
            ->select(['user_id', 'count(DISTINCT player_id) as total_point'])
            ->where(['match_id' => $match_id, 'team_id' => $team_id])
            ->groupBy('user_id')
            ->orderBy('total_point DESC')
            ->limit(10)
            ->asArray()
            ->all();

        $rank = 1;

        if (!empty($userPointOfMatch)) {
            $winners = [];
            foreach ($userPointOfMatch as $row) {
                array_push($winners, $row['user_id']);
                $model = \common\models\SeasonMatchWinner::find()
                    ->where([
                        'user_id' => $row['user_id'],
                        'match_id' => $match_id,
                        'team_id' => $team_id,
                        'season' => \Yii::$app->season->getCurrentSeason(),
                    ])->one();
                $model = $model ? $model : new \common\models\SeasonMatchWinner;
                $model->season = \Yii::$app->season->getCurrentSeason();
                $model->user_id = $row['user_id'];
                $model->match_id = $match_id;
                $model->team_id = $team_id;
                $model->points = $row['total_point'];
                $model->rank = $rank;
                if ($model->save()) {
                    if ($rank == 1) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_1');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_1');

                    } else if ($rank == 2) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_2');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_2');

                    } else if ($rank == 3) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_3');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_3');

                    } else if ($rank == 4) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_4');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_4');

                    } else if ($rank == 5) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_5');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_5');

                    } else if ($rank == 6) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_6');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_6');

                    } else if ($rank == 7) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_7');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_7');

                    } else if ($rank == 8) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_8');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_8');

                    } else if ($rank == 9) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_9');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_9');
                    } else if ($rank == 10) {
                        $token = \Yii::$app->token->getTokenValue('winner_position_10');
                        $token_type_id = \Yii::$app->token->getTokenId('winner_position_10');
                    } else {
                        $token = 1;
                        $token_type_id = "";
                    }

                    $model2 = new \common\models\UserTokenTransaction();
                    $model2->user_id = $model->user_id;
                    $model2->transaction_type = 10;
                    $model2->token = $token;
                    $model2->token_type_id = $token_type_id ? $token_type_id : \Yii::$app->token->getTokenId('submit_rate_card');
                    $model2->remark = 'For having been Fan Winner in ' . $this->match_name($match_id) . ' in position ' . $rank;
                    $model2->created_by = $model->user_id;
                    if ($model2->save(false)) {
                        \Yii::$app->token->updateUserToken($model2->user_id, $token);
                    }

                    $rank++;
                } else {
                    \Yii::info($model->errors, 'WinnerIsNotSaved');
                }
            }
            try {
                \Yii::$app->notification->savePushByAdmin(
                    'Fan Winner � stato annunciato',
                    'Congratulazioni! Sei il Fan Winner di ' . $this->match_name($match_id),
                    'winner',
                    ['match_id' => $model->match_id, 'type' => 'winner'],
                    \Yii::$app->security->generateRandomString(12),
                    [],
                    $winners
                );
            } catch (\Exception $e) {

            }
        }

    }
    public function calcPointForMatch($match)
    {
        \Yii::$app->match->_calculatePoint($match->id, $match->team_home_id);
        \Yii::$app->match->_calculatePoint($match->id, $match->team_away_id);
        $match->is_point_calculated = 1;
        if ($match->save()) {
            //Implemented by fejan for result notification to voted users
            $checkNotification = \common\models\Notification::find()
                ->where(['type' => 'result'])
                ->andFilterWhere(['like', 'data', '"match_id": ' . $match->id])
                ->count();
            if ($checkNotification == 0) {
                $votedUsers = \common\models\UserMatchVote::find()
                    ->where(['match_id' => $match->id])
                    ->groupBy('user_id')
                    ->asArray()
                    ->all();

                $user_ids = \yii\helpers\ArrayHelper::getColumn($votedUsers, 'user_id');

                \Yii::$app->notification->savePushByAdmin(
                    'The results of ' . \Yii::$app->match->match_name($match->id) . ' have been published',
                    'Check now if you are among the winners ',
                    'result',
                    ['match_id' => $match->id, 'type' => 'result'],
                    \Yii::$app->security->generateRandomString(12),
                    [],
                    $user_ids,
                );
            }
            return ['status' => true];
        } else {
            \Yii::info($match->errors, 'FailedToUpdateMatch');
            return ['status' => false, 'message' => json_encode($match->errors)];
        }
    }
}