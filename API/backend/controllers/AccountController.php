<?php

namespace backend\controllers;

use common\models\Season;
use common\models\SeasonMatch;
use common\models\SeasonTeam;
use common\models\SeasonTeamPlayer;
use common\models\User;
use common\models\UserData;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AccountController implements the CRUD actions for User model.
 */
class AccountController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'change-password', 'user-credit', 'dashboard', 'sync-setup', 'monthly-users', 'update-user-team'],
                'rules' => [
                    [
                        'actions' => ['index', 'update-user-team', 'change-password', 'user-credit', 'dashboard', 'monthly-users', 'db-migrate', 'migrate-team'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],

                ],
            ],
        ];
    }
    public function actionTest()
    {
        //\Yii::$app->notification->savePush('Push test at ' . time(), 'Push test by developer at ' . time(), json_encode(['test' => 'test', 'dev' => 'dev']), '0909', $per_page = 1000);
        //\Yii::$app->notification->sendPush();
    }

    //migrate old golden ball to token
    public function actionTokenCalc()
    {
        $user = \common\models\UserData::find()
            ->where(['between', 'user_id', 10001, 30500])
            ->all();

        foreach ($user as $row) {
            if ($row->old_golden_ball > 0) {
                $row->token = $row->old_golden_ball * 4 + $row->token;
                if (!$row->save()) {
                    print_r($row);
                    die;
                } else {
                    $model = new UserTokenTransaction();
                    $model->user_id = $row->user_id;
                    $model->transaction_type = 10;
                    $model->token = $row->old_golden_ball * 4;
                    $model->token_type_id = 224;
                    $model->remark = "Conversione della pallina d'oro in gettone.";
                    $model->created_by = Yii::$app->user->identity->id;
                    if (!$model->save()) {
                        print_r($model);
                    }
                }
            }
        }
        echo 'user updated';
    }

    public function actionDbMigrate()
    {
        // set_time_limit(60 * 60 * 60);
        // phpinfo();
        // die;
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('register_user')
            ->where(['>=', 'user_id', 26638])
            //->limit(1000)
            ->all();
        //->one();

        foreach ($rows as $data) {
            $model = \common\models\User::find()->where(['email' => $data['email']])->one();

            $model = $model ? $model : new \common\models\User;
            $model->email = $data['email'];

            if ($data['password_normal'] != '0') {
                $model->setPassword($data['password_normal']);
            } else {
                $model->password_hash = '';
            }

            $model->status = $data['is_active'] == 1 ? 10 : 9;
            $model->generateAuthKey();
            if ($data['created_date'] == '0000-00-00') {
                $model->created_at = time();
            } else {
                $model->created_at = strtotime($data['created_date']);
            }

            //dealing here with social login acoounts
            $model->is_social = !empty($data['provider']) ? 1 : 0;

            if ($model->save()) {
                //auth assignment is here
                \common\models\User::roleAssignment($model->id, 'user');

                $socialModel = \common\models\UserSocialAuth::find()->where(['user_id' => $model->id])->one();
                if (empty($socialModel)) {
                    if ($model->is_social == 1) {
                        $socialModel = new \common\models\UserSocialAuth;
                        $socialModel->user_id = $model->id;
                        $socialModel->provider_key = $data['unique_id'];
                        if ($data['provider'] == 'facebook') {
                            $socialModel->provider_type = 1;
                        } elseif ($data['provider'] == 'google') {
                            $socialModel->provider_type = 2;
                        } elseif ($data['provider'] == 'apple') {
                            $socialModel->provider_type = 3;
                        }
                        $socialModel->save();
                    }
                }

                $userData = \common\models\UserData::find()->where(['user_id' => $model->id])->one();
                $userData = $userData ? $userData : new \common\models\UserData();
                $userData->user_id = $model->id;

                if (empty($data['name'])) {
                    $tempName = explode('@', $data['email']);
                    $userData->first_name = $tempName[0];
                    $userData->last_name = $tempName[0];
                } else if (str_word_count(trim($data['name'])) == 1) {
                    $userData->first_name = trim($data['name']);
                    $userData->last_name = trim($data['name']);
                } else if (str_word_count(trim($data['name'])) >= 2) {
                    $nameArray = explode(' ', trim($data['name']), 2);
                    $userData->first_name = isset($nameArray[0]) ? $nameArray[0] : trim($data['name']);
                    $userData->last_name = isset($nameArray[1]) ? $nameArray[1] : trim($data['name']);
                } else {
                    $userData->first_name = "Unknow";
                    $userData->last_name = "Unknow";
                }

                $userData->username = $data['userid'] . '___' . rand(1, 1000); //null when social login

                if (empty($data['userid'])) {
                    $tempName = explode('@', $data['email']);
                    $userData->username = $tempName[0] . '___' . rand(1, 1000);
                }

                $ciPhoto = 'https://fanratingitalia.com/' . $data['profile_path'];

                $checkPhoto = $this->url_exists($ciPhoto) ? "Exists" : 'Not Exists';
                // image logic here
                if (!empty($data['profile_path']) && $checkPhoto == "Exists") {
                    $BasePath = Yii::$app->basePath . '/../img_assets/temp/'; //tempoary path to save image
                    \yii\helpers\FileHelper::createDirectory($BasePath);

                    $content = file_get_contents($ciPhoto); // get content
                    $prefix = "uploads/ProfilePicture/";
                    $index = strpos($data['profile_path'], $prefix) + strlen($prefix);
                    $oldFileName = substr($data['profile_path'], $index);
                    $oldFileName = 'fr-v5' . $oldFileName;
                    file_put_contents($BasePath . $oldFileName, $content); // put image from basepath
                    $storage = Yii::$app->get('storage');
                    $storage->upload('user_photo/' . $oldFileName, $BasePath . $oldFileName); // upload here
                    $filepath = $BasePath . $oldFileName;
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }
                    $link = $storage->getUrl('user_photo/' . $oldFileName);
                    $userData->photo = $link;
                } else {
                    $userData->photo = '';
                }

                if (empty($data['gender'])) {
                    $userData->gender = 1;
                } else {
                    $userData->gender = $data['gender'] == 'male' ? 1 : 2;
                }

                if ($data['date_of_birth'] == '0000-00-00') {
                    $userData->birth_date = '';
                    $userData->birth_year = '';
                } else {
                    $userData->birth_date = $data['date_of_birth'];
                    $userData->birth_year = date_format(date_create($userData->birth_date), 'Y');
                }
                $userData->city_id = $data['city'] == 0 ? '' : $data['city'];
                $userData->education_id = $data['educational_id'] == 0 ? '' : $data['educational_id'];
                $userData->job_id = $data['job'] == 0 ? '' : $data['job'];

                //token logic here
                if ($data['total_points'] == 60 && $data['total_points'] <= 119) {
                    $token = 5;
                } else if ($data['total_points'] >= 120 && $data['total_points'] <= 209) {
                    $token = 8;
                } else if ($data['total_points'] >= 210 && $data['total_points'] <= 239) {
                    $token = 9;
                } else if ($data['total_points'] == 240) {
                    $token = 10;
                } else if ($data['total_points'] >= 241 && $data['total_points'] <= 420) {
                    $token = 8;
                } else if ($data['total_points'] >= 421 && $data['total_points'] <= 450) {
                    $token = 9;
                } else if ($data['total_points'] >= 451 && $data['total_points'] <= 480) {
                    $token = 10;
                } else if ($data['total_points'] >= 481 && $data['total_points'] <= 510) {
                    $token = 11;
                } else if ($data['total_points'] >= 511 && $data['total_points'] <= 600) {
                    $token = 10;
                } else if ($data['total_points'] >= 601 && $data['total_points'] <= 660) {
                    $token = 8;
                } else if ($data['total_points'] >= 661 && $data['total_points'] <= 690) {
                    $token = 9;
                } else if ($data['total_points'] >= 691 && $data['total_points'] <= 710) {
                    $token = 10;
                } else if ($data['total_points'] >= 711 && $data['total_points'] <= 740 || $data['total_points'] > 740) {
                    $token = 11;
                } else {
                    $token = 0;
                }
                $userData->token = $token;
                $teamarray = [1 => 39, 2 => 42, 3 => 44, 4 => 40, 5 => 41, 32 => 43, 33 => 37, 34 => 8, 35 => 6, 36 => 9, 37 => 1, 74 => 38, 75 => 5, 76 => 4, 77 => 22, 78 => 7, 79 => 17, 80 => 10, 81 => 3, 82 => 11];
                $userData->team_id = isset($teamarray[$data['supported_team_id']]) ? $teamarray[$data['supported_team_id']] : null;

                $userData->level_id = 1;

                if (!$userData->save()) {
                    echo '<pre>';
                    print_r($userData);
                    die;
                }
            } else {
                // print_r($model->errors);die;
            }
        }
        echo 'rows migrated';
    }

    public function url_exists($url)
    {
        $hdrs = @get_headers($url);
        return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/', $hdrs[0]) : false;
    }
    public function actionMonthlyUsers($year)
    {
        $this->layout = false;

        $data = \Yii::$app->dashboard->monthlyUsers($year);
        $html = $this->renderAjax('_monthly_users', ['data' => $data]);
        $res = ['status' => true, 'html' => $html];
        echo json_encode($res);die;
    }
    public function actionDashboard()
    {
        $seasonQuery = Season::find()->asArray()->all();
        $season = [];
        if (isset($seasonQuery)) {
            $season = yii\helpers\ArrayHelper::getColumn($seasonQuery, 'season');
        }

        $date = date('Y-m-d', strtotime('-7 days'));
        $dateBefore = strtotime($date);

        $quiz = (new \yii\db\Query())
            ->select(['quiz.id', 'quiz.quiz_title', 'COUNT(DISTINCT(quiz_answer.user_id)) as quiz_count'])
            ->from('quiz')
            ->leftJoin('quiz_answer', 'quiz_answer.quiz_id = quiz.id')
            ->where(['between', 'quiz.created_at', $dateBefore, time()])
            ->one();

        $survey = (new \yii\db\Query())
            ->select(['survey.id', 'survey.sponsored_by', 'COUNT(DISTINCT(survey_user_selected_option.user_id)) as survey_count'])
            ->from('survey')
            ->leftJoin('survey_user_selected_option', 'survey_user_selected_option.survey_id = survey.id')
            ->where(['between', 'created_at', $dateBefore, time()])
            ->one();

        $notificationquery = \common\models\Notification::find();

        return $this->render('dashboard', [
            'season' => $season,
            'quiz' => $quiz,
            'survey' => $survey,
            'totalUsers' => User::find()->count(),
            'totalPlayers' => SeasonTeamPlayer::find()->count(),
            'totalTeams' => SeasonTeam::find()->count(),
            'totalMatches' => SeasonMatch::find()->count(),
            'notification_count' => [
                'survey' => [
                    'today' => $notificationquery->where(['type' => 'survey'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'survey'])->count(),
                ],
                'quiz' => [
                    'today' => $notificationquery->where(['type' => 'quiz'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'quiz'])->count(),
                ],
                'news' => [
                    'today' => $notificationquery->where(['type' => 'news'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'news'])->count(),
                ],
                'stream' => [
                    'today' => $notificationquery->where(['type' => 'live_stream'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'live_stream'])->count(),
                ],
                'vote' => [
                    'today' => $notificationquery->where(['type' => 'vote'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'vote'])->count(),
                ],
                'winner' => [
                    'today' => $notificationquery->where(['type' => 'winner'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'winner'])->count(),
                ],
                'video' => [
                    'today' => $notificationquery->where(['type' => 'video'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'video'])->count(),
                ],
                'result' => [
                    'today' => $notificationquery->where(['type' => 'result'])->andWhere(['between', 'created_at', $dateBefore, time()])->count(),
                    'all' => $notificationquery->where(['type' => 'result'])->count(),
                ],
            ],
        ]);
    }
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel(Yii::$app->user->identity->id);
        $model->scenario = 'account';
        $old_photo = $model->photo;

        $model->email = Yii::$app->user->identity->email;
        if ($model->load(Yii::$app->request->post())) {
            if (UploadedFile::getInstance($model, 'photo')) {
                $model->photo = UploadedFile::getInstance($model, 'photo');
                $model->photo = Yii::$app->img->upload(UploadedFile::getInstance($model, 'photo'), $s3_folder = "user_photo", $size = 500, $old_photo);
            } else {
                $model->photo = $old_photo;
            }
            $model->birth_year = date_format(date_create($model->birth_date), 'Y');
            if ($model->save()) {
                Yii::$app->session->setFlash('success_profile', \Yii::t('app', 'Your profile has been updated.'));
                return $this->redirect(['index']);
            } else {
                \Yii::$app->general->throwError(json_encode($model->errors));
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
    public function actionChangePassword()
    {
        $model = new \frontend\models\ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->update()) {
            Yii::$app->session->setFlash('success_password', \Yii::t('app', 'Your password has been successfully updated.'));
            return $this->redirect(['change-password']);
        }

        return $this->render('change_password', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = UserData::find()->where(['user_id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
