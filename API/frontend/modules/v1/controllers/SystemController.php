<?php

namespace frontend\modules\v1\controllers;

use common\models\Streaming;
use common\models\UserCityList;
use common\models\UserEducationList;
use common\models\UserJoblevelList;
use common\models\UserLevelList;
use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class SystemController extends ActiveController
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
            'stream-list',
            '',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['common', 'dropdowns'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['common', 'dropdowns'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }
    private function convert($inputs)
    {
        $data = [];
        if ($inputs) {
            foreach ($inputs as $k => $input) {
                //$data[$k]['id'] = $k;
                $data[$k]['label'] = $input->name;
                $data[$k]['value'] = $input->id;
            }
        }
        return $data;
    }

    private function convertCountry($countries)
    {
        $data = [];
        if ($countries) {
            foreach ($countries as $k => $input) {
                $temp = [
                    'label' => $input,
                    'value' => $k,
                ];
                array_push($data, $temp);
            }
        }
        return $data;
    }
    public function actionDropdowns()
    {
        $cities = UserCityList::find()->all();
        $educations = UserEducationList::find()->all();
        $jobs = UserJoblevelList::find()->all();
        $countries = \Yii::$app->general->country();
        $levels = UserLevelList::find()->all();

        return ['status' => true,
            'data' => [
                'cities' => $this->convert($cities),
                'educations' => $this->convert($educations),
                'jobs' => $this->convert($jobs),
                'countries' => $this->convertCountry($countries),
                'fans' => [
                    [
                        'label' => 'Abbonato TV',
                        'value' => 'Abbonato TV',
                    ],
                    [
                        'label' => 'Abbonato allo stadio',
                        'value' => 'Abbonato allo stadio',
                    ],
                    [
                        'label' => 'Occasionale',
                        'value' => 'Occasionale',
                    ],
                ],
                'levels' => [
                    'current_level' => \Yii::$app->user->identity->userData->level,
                    'data' => $levels,
                ],
            ],
        ];
    }
    public function actionCommon()
    {
        $is_survey_available = \common\models\Survey::find()->where(['is_active' => 1])->exists();
        $is_quiz_available = \common\models\Quiz::find()->where(['is_active' => 1])->exists();
        return [
            'status' => true,
            'data' => [
                'is_quiz_available' => $is_quiz_available,
                'is_survey_available' => $is_survey_available,
            ],
        ];
    }
    public function actionStreamList($is_watch = 0)
    {
        $streams = Streaming::find()->orderBy('is_live DESC')->asArray()->all();
        if (isset($streams)) {
            $data = [];
            foreach ($streams as $key => $value) {
                $data[$key]['id'] = $value['id'];
                $data[$key]['title'] = $value['title'];
                $data[$key]['is_live'] = $value['is_live'];
                $data[$key]['thumb_img'] = $value['thumb_img'];
                $data[$key]['is_external'] = $value['is_external'];
                $data[$key]['video_url'] = $value['video'];
                $data[$key]['external_link'] = $value['external_link'];
                $data[$key]['created_at'] = $value['created_at'];
            }
            return ['status' => true, 'data' => $data];
        }
        return ['status' => false, 'data' => []];

    }
}
