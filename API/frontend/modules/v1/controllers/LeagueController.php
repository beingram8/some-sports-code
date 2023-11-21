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

class LeagueController extends ActiveController
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
            'leagues-for-guest',
        ];

        return $behaviors;
    }

    public function actionLeaguesForGuest()
    {
        $league_list = \common\models\SeasonLeague::find()
            ->where(['is_main' => 1])
            ->all();
        return ['status' => true, 'data' => ['league_list' => $league_list]];
    }
}