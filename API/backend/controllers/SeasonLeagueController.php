<?php

namespace backend\controllers;

use common\models\SeasonLeague;
use common\models\SeasonLeagueSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SeasonLeagueController implements the CRUD actions for SeasonLeague model.
 */
class SeasonLeagueController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new SeasonLeagueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionFetch($season = "")
    {
        $season = $season ? $season : date('Y');

        if (isset($season)) {
            $leagues = \Yii::$app->fetch->fetchLeagues($season);
            if ($leagues['status']) {
                foreach ($leagues['data'] as $league) {
                    $model = SeasonLeague::find()->where(['season' => $season, 'api_league_id' => $league['league']['id']])->one();
                    $model = $model ? $model : new SeasonLeague();
                    $model->season = $season;
                    $model->name = $league['league']['name'];
                    $model->type = $league['country']['name'] == "World" ? 2 : 1;
                    $model->api_league_id = $league['league']['id'];
                    $model->api_response = json_encode($league);
                    if (!$model->save()) {
                        \Yii::$app->general->throwError(json_encode($model->errors));
                    }
                }
                return $this->redirect(['index', 'SeasonLeagueSearch[season]' => $model->season]);
            } else {
                \Yii::$app->general->throwError($leagues['message']);
            }
        }
    }
    protected function findModel($id)
    {
        if (($model = SeasonLeague::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionUpdateIsMain()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            $data['option'] = $data['option'] == 0 ? 1 : 0;
            SeasonLeague::updateAll(['is_main' => $data['option']], ['id' => $data['id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }
}