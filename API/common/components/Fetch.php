<?php

namespace common\components;

use Yii;

class Fetch extends \yii\base\Component
{
    public function apiUrl()
    {
        return "https://v3.football.api-sports.io/";
    }

    public function commonCurl($action)
    {
        $url = $this->apiUrl() . $action;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: aefadfad-8f66-6afb-1940-3f8dad0b8685",
                "x-rapidapi-host: " . \Yii::$app->params['football_api_host'],
                "x-rapidapi-key:" . \Yii::$app->params['football_api_key'],
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['status' => false, 'message' => "cURL Error #:" . $err];
        }
        $data = json_decode($response, true);
        if ($data['errors']) {
            return ['status' => false, 'message' => \Yii::$app->general->throwError(json_encode($data['errors']))];
        }
        return ['status' => true, 'data' => \yii\helpers\ArrayHelper::getValue($data, 'response')];
    }
    public function fetchFixtures($season, $league_id)
    {
        $action = 'fixtures?season=' . $season . '&league=' . $league_id;
        $fixtures = $this->commonCurl($action);
        return $fixtures;
    }
    public function fetchTeams($season, $league_id)
    {
        $action = 'teams?season=' . $season . '&league=' . $league_id;
        $teams = $this->commonCurl($action);
        return $teams;
    }
    public function fetchTeamsByLeagueAndCountry($season, $league_id, $country_code)
    {
        if ($country_code) {
            $country = \Yii::$app->general->country($country_code);
            $action = 'teams?season=' . $season . '&league=' . $league_id . '&country=' . $country;
        } else {
            $action = 'teams?season=' . $season . '&league=' . $league_id;
        }
        $teams = $this->commonCurl($action);
        return $teams;
    }

    public function fetchLeagues($season, $country)
    {
        if ($country == "world") {
            $action = "leagues?season=" . $season . '&country=world';
            $response = $this->commonCurl($action);
            if ($response['status'] == false) {
                return ['status' => true, 'data' => $response];
            }
        } else {
            $action = "leagues?season=" . $season . '&code=' . $country;
            $response = $this->commonCurl($action);
            if ($response['status'] == false) {
                return ['status' => true, 'data' => $response];
            }
        }
        return $response;
    }
    public function fetchPlayersByMatchByTeam($fixture_id, $team_id = "")
    {
        if ($team_id) {
            $action = 'fixtures/players?fixture=' . $fixture_id . '&team=' . $team_id;
        }
        $action = 'fixtures/players?fixture=' . $fixture_id;
        $players = $this->commonCurl($action);
        return $players;
    }
    public function fetchPlayer($player_id, $season)
    {
        $action = 'players?id=' . $player_id . '&season=' . $season;
        $player = $this->commonCurl($action);
        return $player;
    }
    public function fetchSubstitutes($fixture_id, $team_id)
    {
        $action = 'fixtures/events?fixture=' . $fixture_id . '&team=' . $team_id . '&type=subst';
        $substitutes = $this->commonCurl($action);
        return $substitutes;
    }
    public function fetchCoach($coach_id, $season)
    {
        $action = 'coachs?id=' . $coach_id;
        $player = $this->commonCurl($action);
        return $player;
    }
    public function fetchFixture($fixture_id)
    {
        $action = 'fixtures?id=' . $fixture_id;
        $fixture = $this->commonCurl($action);
        return $fixture;
    }
    public function fetchLineupForFixture($fixture_id)
    {
        $action = 'fixtures/lineups?fixture=' . $fixture_id;
        $fixture = $this->commonCurl($action);
        return $fixture;
    }
}