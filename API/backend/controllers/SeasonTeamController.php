<?php

namespace backend\controllers;

use common\models\SeasonTeam;
use common\models\SeasonTeamSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SeasonTeamController implements the CRUD actions for SeasonTeam model.
 */
class SeasonTeamController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'update-active'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    public function actionFetch($season = "", $league_id)
    {
        $season = $season ? $season : date('Y');

        if (isset($season)) {
            $teams = \Yii::$app->fetch->fetchTeams($season, $league_id);
            if ($teams['status']) {
                foreach ($teams['data'] as $team) {
                    $model = SeasonTeam::find()->where(['season' => $season, 'api_team_id' => $team['team']['id']])->one();
                    $model = $model ? $model : new SeasonTeam();
                    $model->season = $season;
                    $model->name = $team['team']['name'];
                    $model->logo = $team['team']['logo'];
                    $model->api_team_id = $team['team']['id'];
                    $model->api_response = json_encode($team);
                    if (!$model->save()) {
                        \Yii::$app->general->throwError(json_encode($model->errors));
                    }
                }
                return $this->redirect(['index', 'SeasonTeamSearch[season]' => $model->season]);
            } else {
                \Yii::$app->general->throwError($leagues['message']);
            }
        }
    }

    public function actionUpdateActive()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            $data['option'] = $data['option'] == 0 ? 1 : 0;
            SeasonTeam::updateAll(['is_active' => $data['option']], ['id' => $data['team_id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    public function actionUpdateIsMain()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            $data['option'] = $data['option'] == 0 ? 1 : 0;
            SeasonTeam::updateAll(['is_main_team' => $data['option']], ['id' => $data['id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }

    public function actionUpdateIsNational()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            $data['option'] = $data['option'] == 0 ? 1 : 0;
            SeasonTeam::updateAll(['is_national_team' => $data['option']], ['id' => $data['id']]);
            return json_encode(array('data' => true));
        }
        return json_encode(array('data' => false));
    }
    /**
     * Lists all SeasonTeam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeasonTeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = SeasonTeam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}