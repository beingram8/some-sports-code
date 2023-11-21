<?php

namespace backend\controllers;

use common\models\UserMatchVote;
use common\models\UserMatchVoteSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserMatchVoteController implements the CRUD actions for UserMatchVote model.
 */
class MatchVoteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'vote-users'],
                'rules' => [
                    [
                        'actions' => ['index', 'vote-users'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    public function actionVoteUsers($match_id)
    {
        $searchModel = new UserMatchVoteSearch();
        $searchModel->match_id = $match_id;
        $dataProvider = $searchModel->searchVoteUsers(Yii::$app->request->queryParams);

        return $this->render('vote-users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all UserMatchVote models.
     * @return mixed
     */
    public function actionIndex($match_id = "", $team_id = "", $player_id = "")
    {

        $searchModel = new UserMatchVoteSearch();
        if ($match_id) {
            $searchModel->match_id = $match_id;
        }
        if ($team_id) {
            $searchModel->team_id = $team_id;
        }
        if ($player_id) {
            $searchModel->player_id = $player_id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserMatchVote model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserMatchVote model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserMatchVote();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserMatchVote model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
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
        if (($model = UserMatchVote::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
