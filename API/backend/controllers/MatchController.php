<?php
namespace backend\controllers;

use common\models\SeasonMatch;
use common\models\SeasonMatchSearch;
use Yii;
use yii\web\Controller;

class MatchController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'players', 'enable-vote', 'calculate-point', 'add-url', 'fetch-player-manually'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                      'actions' => ['fetch-all-player-manually'],
                      'allow' => true,
                    ],
                ],
               
            ],
        ];
    }
    public function actionFetchAllPlayerManually()
    {
        $matches = \common\models\SeasonMatch::find()->all();
        $seasonMatchPlayer = \common\models\SeasonMatchPlayer::find()->orderBy('match_id DESC')->one();

        if($seasonMatchPlayer) {
            \common\models\SeasonMatchPlayer::deleteAll(['match_id' => $seasonMatchPlayer->match_id]);
        }
        if ($matches) {
            foreach ($matches as $match) {
                $seasonMatchPlayers = \common\models\SeasonMatchPlayer::find()->where(['match_id' => $match->id])
                        ->one();

                if($seasonMatchPlayers) {

                } else {
                    Yii::$app->match->setPlayerAfterMatch($match->api_match_id);
                }
                
            }
        }
    }
    public function actionFetchPlayerManually($api_match_id)
    {
        $player_response = \Yii::$app->match->setPlayerAfterMatch($api_match_id);
        if ($player_response['status']) {
            Yii::$app->session->setFlash('success', "Match has been updated.");
        } else {
            Yii::$app->session->setFlash('error', $player_response['message']);
        }
        $id = \Yii::$app->match->idFromApiId($api_match_id);
        return $this->redirect(['players', 'id' => !empty($id) ? $id : '']);
    }
    public function actionCalculatePoint($fixture_id)
    {
        $match = \common\models\SeasonMatch::find()->where(['is_vote_enabled' => 2, 'is_point_calculated' => 0, 'api_match_id' => $fixture_id])->one();
        if ($match) {
            $res = \Yii::$app->match->calcPointForMatch($match);
        } else {
            $res = ['status' => false, 'message' => 'Sorry there are no any match.'];
        }
        echo json_encode($res);die;
    }

    public function actionAddUrl()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $match_url = isset($_GET['match_url']) ? $_GET['match_url'] : "";
        if (!empty($id) && !empty($match_url)) {
            $model = \common\models\SeasonMatch::find()
                ->where(['id' => $id])->one();
            if (!empty($model)) {
                $model->match_url = $match_url;
                if ($model->save(false)) {
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionEnableVote($fixture_id)
    {
        $match = \common\models\SeasonMatch::find()
            ->where(['<', 'match_timestamp', time()])
            ->andWhere(['is_vote_enabled' => 0])
            ->andWhere(['api_match_id' => $fixture_id])
            ->one();
        if ($match) {
            Yii::$app->match->updateFixture($fixture_id);
            $res = ['status' => true, 'message' => ''];
        } else {
            $res = ['status' => false, 'message' => 'Sorry you can not enable vote for this match.'];
        }
        echo json_encode($res);die;
    }
    public function actionIndex()
    {
        $fixtures = "";
        $model = new \backend\models\FetchFixtureForm;
        $match = new SeasonMatch();
        if ($model->load(Yii::$app->request->post())) {
            $fixtures = \Yii::$app->fetch->fetchFixtures($model->season, $model->league_id);
        }

        $searchModel = new SeasonMatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'fixtures' => $fixtures,
            'model' => $model,
            'match' => $match,
        ]);
    }

    public function actionUpdateMatchDay()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            SeasonMatch::updateAll(['match_day' => $data['match_day']], ['id' => $data['match_id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    public function actionUserVotedMatch()
    {
        $searchModel = new SeasonMatchSearch();
        $dataProvider = $searchModel->searchMatch(Yii::$app->request->queryParams);

        return $this->render('user-voted-match', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUpdate($id)
    {
        // $playerInfo = \Yii::$app->fetch->fetchPlayer($player_api_id, $season);
        // print_r($playerInfo);die;
        $model = $this->findModel($id);
        $fixtureResp = Yii::$app->match->updateFixture($model->api_match_id);
        if ($fixtureResp['status']) {
            Yii::$app->session->setFlash('success', "Match has been updated.");
        } else {
            Yii::$app->session->setFlash('error', $fixtureResp['message']);
        }
        return $this->redirect(['index']);
    }
    public function actionPlayers($id)
    {
        $model = $this->findModel($id);

        return $this->render('players', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = SeasonMatch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}