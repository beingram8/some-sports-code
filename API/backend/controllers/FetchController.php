<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

class FetchController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['get-matches', 'fetch-match-players', 'fetch-league-based-fixtures', 'get-teams-by-season-league-country', 'fetch-league', 'fetch-fixture'],
                'rules' => [
                    [
                        'actions' => ['get-matches', 'fetch-match-players', 'index', 'get-teams-by-season-league-country', 'get-leagues-by-season-and-country', 'fetch-league-based-fixtures', 'fetch-fixture', 'fetch-league'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    public function actionFetchMatchPlayers($fixture_id)
    {
        $data = \Yii::$app->match->setPlayerAfterMatch($fixture_id);
        echo json_encode($data);die;
    }
    public function actionFetchFixture($fixture_id)
    {
        $fixtureResp = Yii::$app->match->updateFixture($fixture_id);
        echo json_encode($fixtureResp);die;
    }
    public function actionGetMatches()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $output = [];
                $season = $parents[0];
                $league_id = $parents[1];
                if ($season && $league_id) {
                    $data = \Yii::$app->fetch->fetchFixtures($season, $league_id);
                    if ($data['status']) {
                        foreach ($teams['data'] as $team) {
                            array_push($output, array('id' => $team['team']['id'], 'name' => $team['team']['name']));
                        }
                    }
                }
                return ['output' => $output ? $output : ""];
            }
        }
        return ['output' => ''];
    }
    public function actionGetTeamsBySeasonLeagueCountry()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $output = [];
                $season = $parents[0];
                $league_id = $parents[1];
                $league = \common\models\SeasonLeague::find()->where(['id' => $league_id])->one();
                $api_league_id = $league->api_league_id;
                $country = isset($parents[2]) ? $parents[2] : "";
                $country = $country == "world" ? "" : $country;
                if ($season && $league_id) {
                    $teams = \Yii::$app->fetch->fetchTeamsByLeagueAndCountry($season, $api_league_id, $country);
                    if ($teams['status']) {
                        foreach ($teams['data'] as $team) {
                            array_push($output, array('id' => $team['team']['id'], 'name' => $team['team']['name']));
                        }
                    }
                }
                return ['output' => $output ? $output : ""];
            }
        }
        return ['output' => ''];
    }
    public function actionGetLeaguesBySeasonAndCountry()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $output = [];
                $season = $parents[0];
                $country = $parents[1];
                if ($country & $season) {
                    $leagues = \Yii::$app->fetch->fetchLeagues($season, $country);
                    if ($leagues['status']) {
                        foreach ($leagues['data'] as $league) {
                            array_push($output, array('id' => $league['league']['id'], 'name' => $league['league']['name']));
                        }
                    }
                }
                return ['output' => $output ? $output : ""];
            }
        }
        return ['output' => ''];
    }
    //Fetching Leagues
    public function actionFetchLeague()
    {
        $model2 = new \backend\models\FetchLeagueForm;
        if ($model2->load(Yii::$app->request->post())) {
            if (!$model2->validate()) {
                \Yii::$app->general->throwError($model2->errors);
            }
            $leagues = \Yii::$app->fetch->fetchLeagues($model2->season, $model2->country);
            if ($leagues['status']) {
                foreach ($leagues['data'] as $league) {
                    if (in_array($league['league']['id'], $model2->leagues)) {
                        $model = \common\models\SeasonLeague::find()->where(['season' => $model2->season, 'api_league_id' => $league['league']['id']])->one();
                        $model = $model ? $model : new \common\models\SeasonLeague();
                        $model->season = $model2->season;
                        $model->name = $league['league']['name'];
                        $model->logo = $league['league']['logo'];
                        $model->country = $model2->country;
                        $model->api_league_id = $league['league']['id'];
                        $model->api_response = json_encode($league);
                        if (!$model->save()) {
                            \Yii::$app->general->throwError(json_encode($model->errors));
                        }
                    }
                }
                return $this->redirect(['/season-league/index', 'SeasonLeagueSearch[season]' => $model2->season]);
            } else {
                \Yii::$app->general->throwError($leagues['message']);
            }
        }
        return $this->redirect(['/season-league/index']);

    }

    public function actionFetchTeams()
    {
        $model2 = new \backend\models\FetchTeamForm;
        if ($model2->load(Yii::$app->request->post())) {
            if (!$model2->validate()) {
                \Yii::$app->general->throwError($model2->errors);
            }
            $league = \common\models\SeasonLeague::find()->where(['id' => $model2->league_id])->one();

            $country = $model2->team_for_which_country_id == "world" ? "" : $model2->team_for_which_country_id;
            $teams = \Yii::$app->fetch->fetchTeamsByLeagueAndCountry($model2->season, $league->api_league_id, $country);

            if ($teams['status']) {
                foreach ($teams['data'] as $team) {
                    if (in_array($team['team']['id'], $model2->teams)) {
                        $model = \common\models\SeasonTeam::find()->where(
                            ['api_team_id' => $team['team']['id']])->one();
                        $model = $model ? $model : new \common\models\SeasonTeam();
                        $model->season = $model2->season;
                        $model->league_id = $model2->league_id;
                        $model->name = $team['team']['name'];
                        $model->logo = $team['team']['logo'];
                        $model->api_team_id = $team['team']['id'];
                        $model->api_response = json_encode($team);
                        if (!$model->save()) {
                            \Yii::$app->general->throwError(json_encode($model->errors));
                        }
                    }
                }
                return $this->redirect(['/season-team/index']);
            } else {
                \Yii::$app->general->throwError($teams['message']);
            }
        }
        \Yii::$app->general->throwError('Parameters are missing.');
    }


    public function actionUpdateTeamByLeague() {
        $leagues = \common\models\SeasonLeague::find()->where(['is_main' => 1])
                        ->all();
                        foreach($leagues as $league) {
                            echo $league->id."<br>";
                        }
        foreach($leagues as $league) {
            sleep(7);
            $teams = \Yii::$app->fetch->fetchTeams($league->season, $league->api_league_id);

            if ($teams['status']) {
                foreach ($teams['data'] as $team) {
                   
                        $model = \common\models\SeasonTeam::find()->where(
                            ['api_team_id' => $team['team']['id']])->one();
                        if ($model) {
                            $model->season = $league->season;
                            $model->league_id = $league->id;
                            $model->name = $team['team']['name'];
                            $model->logo = $team['team']['logo'];
                            $model->api_team_id = $team['team']['id'];
                            $model->api_response = json_encode($team);
                            $model->save();
                        }
                    
                }
            }
        }
    }
    /**
     * 43 58 59 60 61 62 63 64 65 66 67 68 69 70 71 72 73 74 75
     * 
     */
    // 
    // 26, 44, 51 53, 40, 52, 56, 57
    // 39
    // 68, 69, 79, 43, 60, 74, 73
    public function actionFixMatch() {
        $leagues = \common\models\SeasonLeague::find()->where(['in', 'id', [68, 69, 79, 43, 60, 74, 73]])->all();
        foreach($leagues as $league) {
            
            $oldLeague = \common\models\SeasonLeague::find()->where(['season'=> 2021, 'api_league_id'=>$league->api_league_id])->one();
            $oldLeagueId = $oldLeague->id;
            echo $league->id;
            $match = \common\models\SeasonMatch::updateAll([
                'league_id' => $league->id
            ], [
                'season' => 2022, 'league_id' => $oldLeagueId
            ]);
        }
    }
}