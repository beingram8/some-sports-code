<?php

namespace frontend\modules\v1\controllers;

use common\models\ReferAndEarn;
use common\models\RewardCode;
use common\models\RewardProduct;
use common\models\User;
use common\models\UserData;
use common\models\UserPaymentTransaction;
use common\models\UserSocialAuth;
use frontend\filters\auth\HttpBearerAuth;
use frontend\models\ChangePasswordForm;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

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
                'signup' => ['post'],
                'convert-apple-users' => ['post'],
                'login' => ['post'],
                'add-token' => ['post'],
                'edit-profile' => ['post'],
                'photo' => ['post'],
                'change-password' => ['post'],
                'social-login' => ['post'],
                'social-sign-up' => ['post'],
                'reset-password' => ['post'],
                'forgot-password' => ['post'],
                'buy-vocher' => ['get'],
                'ranking' => ['get'],
                'user-ranking' => ['get'],
                'contact-us' => ['post'],
                'my-team' => ['get'],
                'token-plan' => ['get'],
                'buy-token' => ['get'],
                'token-transaction' => ['get'],
                'refer' => ['get'],
                'user-response' => ['get'],
                'add-document' => ['post'],
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
            'signup',
            'convert-apple-users',
            'login',
            'social-login',
            'social-sign-up',
            'reset-password',
            'forgot-password',
            'options',
            'contact-us',
            'ranking',
            'common',
            'token-plan',
            'verify-email',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['logout', 'user-ranking', 'user-response', 'add-token', 'edit-profile', 'photo', 'change-password', 'buy-vocher', 'my-team', 'token-transaction', 'refer', 'add-document'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['logout', 'user-ranking', 'user-response', 'add-token', 'edit-profile', 'photo', 'change-password', 'buy-vocher', 'my-team', 'token-transaction', 'refer', 'add-document'],
                    'roles' => ['user'], //change this when authorization implemented
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionConvertAppleUsers()
    {
        $email = "sa";
        $user = \common\models\User::find()->where(['email' => $email])->one();
        if ($user) {
            $userData = $user->userData;
            $user_social_auth = \common\models\UserSocialAuth::find()
                ->where(['user_id' => $user->id, 'provider_type' => 3])->one();
            if ($user_social_auth) {

            } else {
                return ['status' => false, 'message' => \Yii::t('app', 'Sorry this user has not account with apple')];
            }
        }
        return ['status' => false,
            'message' => \Yii::t('app', 'Sorry we have no account with this email.')];

    }
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->provider_type) && empty($model->provider_key)) {
                $model->scenario = "create";
            }
            if ($model->validate() && $model->signup()) {
                $user = \common\models\User::find()->where(['email' => $model->email])->one();
                if (!empty($model->provider_type) && !empty($model->provider_key)) {
                    $socialAuth = new UserSocialAuth();
                    $socialAuth->provider_key = $model->provider_key;
                    $socialAuth->provider_type = $model->provider_type;
                    $socialAuth->user_id = $user->id;
                    if ($socialAuth->validate() && $socialAuth->save()) {
                        return $this->makelogin($user, $model->platform, $model->uuid);
                    } else {
                        return array('status' => false, 'message' => Yii::$app->general->error($socialAuth->errors));
                    }
                }
                return $this->makelogin($user, $model->platform, $model->uuid);

                return array('status' => true, 'message' => 'Your account has been created successfully, please login to your account.');

            } else {
                return array('status' => false, 'message' => Yii::$app->general->error($model->errors));
            }
        } else {
            return array('status' => true, 'message' => 'Parameters are missing.');
        }
    }
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->user_type = 'user';
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->getUser();
            $responseData = $this->makelogin($user);
            return $responseData;
        } else {
            return array('status' => false, 'message' => Yii::$app->general->error($model->errors));
        }
    }

    private function makelogin($user)
    {
        if ($user->status == 10) { // Active Account
            $user->generateAccessTokenAfterUpdatingClientInfo(true);
            $teamData = Yii::$app->team->getTeam($user->userData->team_id);
            $user_next_level_point = \Yii::$app->userData->getUserLevelPoint($user->userData->level_id + 1);
            $user_previous_level_point = \Yii::$app->userData->getUserLevelPoint($user->userData->level_id);
            return [
                'status' => true,
                'data' => [
                    'id' => $user->id,
                    'access_token' => $user->access_token,
                    'firstname' => $user->userData->first_name,
                    'lastname' => $user->userData->last_name,
                    'username' => $user->userData->username,
                    'phone' => $user->userData->phone,
                    'email' => $user->email,
                    'status' => $user->status,
                    'gender' => Yii::$app->userData->formatGender($user->userData->gender),
                    'city' => [
                        'label' => !empty($user->userData->city) ? $user->userData->city->name : "",
                        'value' => !empty($user->userData->city) ? $user->userData->city->id : "",
                    ],
                    'country' => [
                        'label' => !empty($user->userData->country_id) ? \Yii::$app->general->getValueFromKey($user->userData->country_id) : '',
                        'value' => !empty($user->userData->country_id) ? $user->userData->country_id : '',
                    ],
                    'education' => [
                        'label' => !empty($user->userData->education) ? $user->userData->education->name : "",
                        'value' => !empty($user->userData->education) ? $user->userData->education->id : "",
                    ],
                    'job' => [
                        'label' => !empty($user->userData->job) ? $user->userData->job->name : "",
                        'value' => !empty($user->userData->job) ? $user->userData->job->id : "",
                    ],
                    'fan' => [
                        'label' => !empty($user->userData->fan) ? $user->userData->fan : "",
                        'value' => !empty($user->userData->fan) ? $user->userData->fan : "",
                    ],
                    'team' => \Yii::$app->team->userTeam($user->userData->team_id),
                    'league' => \Yii::$app->league->getLeagueById($user->userData->league_id),
                    'birth_date' => $user->userData->birth_date,
                    'user_image' => Yii::$app->userData->photo($user->id),
                    'language' => $user->userData->lang,
                    'point' => $user->userData->point,
                    'level' => [
                        'current_level' => !empty($user->userData->level_id) ? \Yii::$app->userData->getUserLevel($user->userData->level_id) : 1,
                        'next_level' => !empty($user->userData->level_id) ? \Yii::$app->userData->getUserLevel($user->userData->level_id + 1) : 1,
                        'progress' => !empty($user->userData->level_id) ? \Yii::$app->userData->getLevelProgress($user->userData->point, $user_next_level_point, $user_previous_level_point) : 0,
                    ],
                    'token' => $user->userData->token,
                    'notification_count' => Yii::$app->push->badgeCount($user->id),
                ],
            ];
        } else if ($user->status == 9) {
            return [
                'status' => false,
                'message' => \Yii::t('app', 'Your account was blocked.'),
            ];
        } else {
            return [
                'status' => false,
                'message' => \Yii::t('app', 'Your account was deleted.'),
            ];
        }
    }

    public function actionSocialSignUp()
    {
        $model = new \frontend\models\SocialForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $socialData = \Yii::$app->userData->getSocialData($model->token, $model->social_type);
            $provider_key = $socialData['provider_key'];
            if (empty($provider_key)) {
                return
                    [
                    'status' => false,
                    'message' => \Yii::t('app', 'Impossibile ottenere i tuoi dati social.'),
                ];
            }
            $userSocial = UserSocialAuth::find()
                ->where(['provider_key' => $provider_key])
                ->andWhere(['provider_type' => $model->social_type])
                ->one();
            if (empty($userSocial)) {
                return [
                    'status' => true,
                    'data' => $socialData,
                ];
            } else {
                return ['status' => false, 'message' => 'Your account is already with us. Please try to log in'];
            }
        } else {
            return array('status' => false, 'message' => 'Parameters are missing');
        }
    }
    public function actionSocialLogin()
    {
        $model = new \frontend\models\SocialForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $socialData = \Yii::$app->userData->getSocialData($model->token, $model->social_type);
            $provider_key = $socialData['provider_key'];
            $userSocial = UserSocialAuth::find()
                ->where(['provider_key' => $provider_key])
                ->andWhere(['provider_type' => $model->social_type])
                ->one();
            if (!empty($userSocial)) {
                $userExist = \common\models\User::find()->where(['id' => $userSocial->user_id])->one();
                if ($userExist) {
                    return $this->makelogin($userExist, $model->platform, $model->uuid);
                } else {
                    return ['status' => false, 'message' => \Yii::t('app', 'Non siamo in grado di ottenere il tuo account. Si prega di provare a registrarsi prima.')];
                }
            } else {
                return ['status' => false, 'message' => \Yii::t('app', 'Non siamo riusciti a trovare il tuo account.')];
            }
        } else {
            return array('status' => false, 'message' => \Yii::t('app', 'Mancano i parametri.'));
        }
    }
    public function actionVerifyEmail($token)
    {
        try {
            $model = new \frontend\models\VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            return array('status' => false, 'message' => $e->getMessage());
        }
        if ($model->verifyEmail()) {
            return array('status' => true);
        }
        return array('status' => false, 'message' => \Yii::t('app', 'Sorry, we are unable to verify your account with provided token.'));
    }

    public function actionAddToken()
    {
        $model = new \common\models\UserUuid;
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = \Yii::$app->user->id;
            if ($model->validate() && $model->save()) {
                return array('status' => true);
            } else {
                return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
            }
        } else {
            return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
        }
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = Yii::$app->user->identity;
            $user->setPassword($model->password);
            if ($user->save(false)) {
                return array('status' => true, 'message' => \Yii::t('app', 'La tua password è stata cambiata con successo.'));
            }
        } else {
            return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
        }
    }

    public function actionResetPassword()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return array('status' => true, 'message' => \Yii::t('app', 'Controlla la tua email per ulteriori istruzioni.'));
            } else {
                return array('status' => true, 'message' => \Yii::t('app', 'Spiacenti, non siamo in grado di reimpostare la password per lindirizzo email fornito.'));
            }
        } else {
            return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
        }
    }

    public function actionForgotPassword($token)
    {
        $model = new ResetPasswordForm();

        if (empty($token) || !is_string($token)) {
            return array('status' => false, 'message' => \Yii::t('app', 'Password reset token cannot be blank.'));
        }

        $user = \common\models\User::findByPasswordResetToken($token);
        if (!$user) {
            return array('status' => false, 'message' => \Yii::t('app', 'Wrong password reset token.'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword($token)) {
            return array('status' => true, 'message' => \Yii::t('app', 'La tua password è stata cambiata con successo.'));
        } else {
            return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
        }
    }

    public function actionLogout()
    {
        $uuid = !empty($_GET['uuid']) ? $_GET['uuid'] : "";
        if (!$uuid) {
            return [
                'status' => false,
                'message' => \Yii::t('app', 'Invalid request.'),
            ];
        }
        $UserUuid = \common\models\UserUuid::find()
            ->where(['user_id' => \Yii::$app->user->id, 'uuid' => $uuid])->one();
        if ($UserUuid) {
            $UserUuid->delete();
            return ['status' => true];
        }
        return ['status' => false];
    }

    public function actionEditProfile()
    {
        $model = new \app\models\UserEditForm;
        $user_id = Yii::$app->user->id;
        $userData = UserData::find()->where(['user_id' => $user_id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $userData->attributes = $model->attributes;
            $userData->birth_year = date_format(date_create($userData->birth_date), 'Y');
            if ($userData->validate() && $userData->save()) {
                $is_animation = $userData->checkProfile();
                $user = \Yii::$app->user->identity;
                $user->generateAccessTokenAfterUpdatingClientInfo(true);
                $teamData = Yii::$app->team->getTeam($userData->team_id);
                $teamData = Yii::$app->team->getTeam($user->userData->team_id);
                $user_next_level_point = \Yii::$app->userData->getUserLevelPoint($userData->level_id + 1);
                $user_previous_level_point = \Yii::$app->userData->getUserLevelPoint($user->userData->level_id);
                return [
                    'status' => true,
                    'data' => [
                        'id' => $userData->user_id,
                        'access_token' => $user->access_token,
                        'firstname' => $userData->first_name,
                        'lastname' => $userData->last_name,
                        'username' => $userData->username,
                        'phone' => $userData->phone,
                        'email' => $user->email,
                        'status' => $user->status,
                        'gender' => Yii::$app->userData->formatGender($userData->gender),
                        'city' => [
                            'label' => !empty($userData->city->name) ? $userData->city->name : '',
                            'value' => !empty($userData->city_id) ? $userData->city_id : '',
                        ],
                        'country' => [
                            'label' => !empty($userData->country_id) ? \Yii::$app->general->getValueFromKey($userData->country_id) : '',
                            'value' => !empty($userData->country_id) ? $userData->country_id : '',
                        ],
                        'education' => [
                            'label' => !empty($userData->education->name) ? $userData->education->name : '',
                            'value' => !empty($userData->education_id) ? $userData->education_id : '',
                        ],
                        'job' => [
                            'label' => !empty($userData->job->name) ? $userData->job->name : '',
                            'value' => !empty($userData->job_id) ? $userData->job_id : '',
                        ],
                        'fan' => [
                            'label' => !empty($userData->fan) ? $userData->fan : "",
                            'value' => !empty($userData->fan) ? $userData->fan : "",
                        ],
                        'level' => [
                            'current_level' => !empty($userData->level_id) ? \Yii::$app->userData->getUserLevel($userData->level_id) : 1,
                            'next_level' => !empty($userData->level_id) ? \Yii::$app->userData->getUserLevel($userData->level_id + 1) : 1,
                            'progress' => !empty($userData->level_id) ? \Yii::$app->userData->getLevelProgress($userData->point, $user_next_level_point, $user_previous_level_point) : 0,
                        ],
                        'team' => \Yii::$app->team->userTeam($userData->team_id),
                        'league' => \Yii::$app->league->getLeagueById($user->userData->league_id),
                        'birth_date' => $userData->birth_date,
                        'user_image' => Yii::$app->userData->photo($userData->user_id),
                        'language' => $user->userData->lang,
                        'point' => $userData->point,
                        'token' => $userData->token,
                        'is_animation' => $is_animation,
                        'notification_count' => Yii::$app->push->badgeCount($userData->user_id),
                    ],
                    'message' => \Yii::t('app', 'Il tuo profilo è stato aggiornato'),
                ];
            } else {
                return array('status' => false, 'message' => Yii::$app->general->error($userData->errors));
            }
        } else {
            return array('status' => false, 'message' => Yii::$app->general->error($model->errors));
        }
    }

    public function actionBuyVocher($reward_id)
    {
        $user = Yii::$app->user->identity;
        $checkReward = RewardCode::find()->where(['reward_id' => $reward_id, 'user_id' => null])->one();
        if (!empty($checkReward)) {
            $product = RewardProduct::find()->where(['id' => $reward_id])->one();

            if ($product->buying_token > $user->userData->token) {
                return ['status' => false, 'message' => \Yii::t('app', 'Non hai abbastanza Fan Coins!')];
            } else {
                $user->userData->token = $user->userData->token - $product->buying_token;
                $user->userData->save(false);

                $checkReward->user_id = $user->userData->user_id;
                $checkReward->save(false);

                $tokenModel = new \common\models\UserTokenTransaction();
                $tokenModel->user_id = $user->userData->user_id;
                $tokenModel->transaction_type = 20;
                $tokenModel->token = $product->buying_token;
                $tokenModel->created_by = $user->userData->user_id;
                $tokenModel->remark = 'For redeeming a prize ' . $product->name . ' voucher';
                $tokenModel->save(false);

                $model = new UserPaymentTransaction();
                $model->user_id = $user->userData->user_id;
                $model->status = 10;
                $model->amount = $product->buying_token;
                $model->description = \Yii::t('app', 'Spend ') . $product->buying_token . ' on ' . $product->name;
                $model->created_by = $user->userData->user_id;
                if ($model->save()) {
                    $subject = Yii::$app->emailtemplate->replace_string_email(["{{app_name}}" => \Yii::$app->params['app_name']], "reedem_code", "subject");
                    $string_array = array(
                        "{{name}}" => $user->userData->first_name . ' ' . $user->userData->last_name,
                        "{{description}}" => $product->reward_description,
                        "{{code}}" => $checkReward->reward_code,
                    );
                    $html = \Yii::$app->emailtemplate->replace_string_email($string_array, "reedem_code");
                    \Yii::$app
                        ->mailer->compose()
                        ->setHtmlBody($html)
                        ->setFrom([\Yii::$app->params['sender_email'] => \Yii::$app->params['app_name']])
                        ->setTo($user->email)
                        ->setSubject($subject)
                        ->send();
                    return ['status' => true, 'data' => $product->reward_description, 'message' => \Yii::t('app', 'Voucher added')];
                }

            }
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Premio scaduto')];
        }
    }

    public function actionPhoto()
    {
        $user = Yii::$app->user->identity;
        $userData = UserData::find()->where(['user_id' => $user->id])->one();
        $oldPhoto = $userData->photo;
        $model = new \frontend\models\ImageForm();
        if (Yii::$app->request->isPost) {
            $model->photo = UploadedFile::getInstance($model, 'photo');
            if (!$model->photo) {
                return array('status' => false, 'message' => \Yii::t('app', 'Seleziona una foto.'));
            }
            if ($model->photo && $model->validate()) {
                $model->photo = Yii::$app->img->upload($model->photo, $s3_folder = "user_photo", $size = 500, $oldPhoto, "image");
            } else {
                return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
            }
            $userData->photo = $model->photo;
            if ($userData->validate() && $userData->save()) {
                $is_animation = $userData->checkProfile();
                $user->generateAccessTokenAfterUpdatingClientInfo(true);
                $teamData = Yii::$app->team->getTeam($userData->team_id);
                $user_next_level_point = \Yii::$app->userData->getUserLevelPoint($userData->level_id + 1);
                $user_previous_level_point = \Yii::$app->userData->getUserLevelPoint($userData->level_id);
                return array('status' => true,
                    'message' => 'Immagine del profilo aggiornata.',
                    'data' => [
                        'id' => $userData->user_id,
                        'access_token' => $user->access_token,
                        'firstname' => $userData->first_name,
                        'lastname' => $userData->last_name,
                        'username' => $userData->username,
                        'email' => $user->email,
                        'phone' => $userData->phone,
                        'status' => $user->status,
                        'gender' => Yii::$app->userData->formatGender($userData->gender),
                        'city' => [
                            'label' => !empty($userData->city->name) ? $userData->city->name : '',
                            'value' => !empty($userData->city_id) ? $userData->city_id : '',
                        ],
                        'education' => [
                            'label' => !empty($userData->education->name) ? $userData->education->name : '',
                            'value' => !empty($userData->education_id) ? $userData->education_id : '',
                        ],
                        'job' => [
                            'label' => !empty($userData->job->name) ? $userData->job->name : '',
                            'value' => !empty($userData->job_id) ? $userData->job_id : '',
                        ],
                        'fan' => [
                            'label' => !empty($userData->fan) ? $userData->fan : "",
                            'value' => !empty($userData->fan) ? $userData->fan : "",
                        ],
                        'level' => [
                            'current_level' => !empty($userData->level_id) ? \Yii::$app->userData->getUserLevel($userData->level_id) : 1,
                            'next_level' => !empty($userData->level_id) ? \Yii::$app->userData->getUserLevel($userData->level_id + 1) : 1,
                            'progress' => !empty($userData->level_id) ? \Yii::$app->userData->getLevelProgress($userData->point, $user_next_level_point, $user_previous_level_point) : 0,
                        ],
                        'birth_date' => $userData->birth_date,
                        'user_image' => $userData->photo == '' ? null : $userData->photo,
                        'team' => \Yii::$app->team->userTeam($userData->team_id),
                        'league' => \Yii::$app->league->getLeagueById($user->userData->league_id),
                        'language' => $user->userData->lang,
                        'point' => $userData->point,
                        'token' => $userData->token,
                        'is_animation' => $is_animation,
                        'notification_count' => Yii::$app->push->badgeCount($userData->user_id),
                    ],
                );
            } else {
                return array('status' => false, 'message' => \Yii::$app->general->error($userData->errors));
            }
        } else {
            return array('status' => false, 'message' => ['errors' => [\Yii::t('user-controller', 'Image is missing.')]]);
        }
    }

    public function actionAddDocument()
    {
        $model = \common\models\ParentConfirmation::find()
            ->where(['user_id' => \Yii::$app->user->id])->one();
        $model = $model ? $model : new \common\models\ParentConfirmation();

        if (Yii::$app->request->isPost) {

            if (\yii\web\UploadedFile::getInstance($model, 'document')) {
                $model->document = Yii::$app->img->upload(UploadedFile::getInstance($model, 'document'), $s3_folder = "parent_confirmation", $size = 500, "image", false);
            }
            $model->user_id = \Yii::$app->user->id;
            if ($model->validate() && $model->save()) {
                return array('status' => true, 'message' => \Yii::t('app', 'Document uploaded successfully'));
            } else {
                return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
            }
        } else {
            return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
        }

    }

    public function actionContactUs()
    {
        $model = new \frontend\models\ContactForm;
        $contactUs = new \common\models\ContactUs;
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->sendEmail()) {
            $contactUs->attributes = $model->attributes;
            $contactUs->status = 0;
            if ($contactUs->save()) {
                return array('status' => true, 'message' => \Yii::t('app', 'Grazie per averci contattato. Ti risponderemo il prima possibile.'));
            } else {
                return array('status' => false, 'message' => \Yii::$app->general->error($contactUs->errors));
            }
        } else {
            return array('status' => false, 'message' => \Yii::$app->general->error($model->errors));
        }
    }

    public function actionRanking()
    {
        $model = new \common\models\UserData();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getUserRanking();
        return ['status' => true, 'data' => $data];
    }

    public function actionUserRanking()
    {
        $model = new \common\models\UserData();
        $model->attributes = \Yii::$app->request->get();
        $data = $model->getUserRanking();
        return ['status' => true, 'data' => $data];
    }

    public function actionMyTeam()
    {
        $selected_team_data = Yii::$app->user->identity->userData->team->seasonTeamPlayers;
        $data = [];
        if (!empty($selected_team_data)) {
            foreach ($selected_team_data as $key => $team_data) {

                // $player_details = json_decode($team_data->api_response, true);
                // $games = !empty($player_details['statistics']) ? \yii\helpers\ArrayHelper::getColumn($player_details['statistics'], 'games') : [];

                $playerdata = \Yii::$app->player->playerInfo($team_data->id);
                $playerdata = \common\models\SeasonMatchPlayer::find()->where(['player_id' => $team_data->id])->one();
                if(!empty($playerdata)) {
                    $item = [];
                    $item['player_id'] = $team_data->id;
                    $item['name'] = $team_data->name;
                    $item['jersey_no'] = $team_data->number;
                    $item['photo'] = $team_data->photo;
                    $item['player_position'] = \Yii::$app->player->positionFullForm($playerdata->position);
                    $item['average_rate'] = Yii::$app->player->getAverageRate($team_data->id);
                    $data[] = $item;
                }

            }
            return array('status' => true, 'data' => [
                'team' => [
                    'name' => Yii::$app->user->identity->userData->team->name,
                    'icon' => Yii::$app->user->identity->userData->team->logo,
                ],
                'team_player' => $data,
            ]);

        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Nessun dato trovato')];
        }

    }

    public function actionCommon()
    {
        $check_quiz = \common\models\Quiz::find()->where(['is_active' => 1])->one();
        $check_survey = \common\models\Survey::find()->where(['is_active' => 1])->one();

        $is_survey_enable = !empty($check_survey) ? true : false;
        $is_quiz_enable = !empty($check_quiz) ? true : false;

        return $response = ['is_survey_enable' => $is_survey_enable, 'is_quiz_enable' => $is_quiz_enable];
    }

    public function actionTokenPlan()
    {
        $plan_details = \common\models\TokenPlan::find()->all();
        if (!empty($plan_details)) {
            $data = [];
            foreach ($plan_details as $key => $value) {
                $data[$key]['id'] = $value['id'];
                $data[$key]['token'] = $value['token'];
                $data[$key]['price'] = number_format($value['price'], 2);
                $data[$key]['name'] = $value['name'];
                $data[$key]['reel_amount'] = number_format($value['reel_amount'], 2);
            }
            return ['status' => true, 'data' => $data];

        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Nessun dato trovato')];
        }
    }

    public function actionTokenTransaction()
    {
        $model = \common\models\UserTokenTransaction::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->all();
        if (!empty($model)) {
            $data = [];
            foreach ($model as $key => $value) {
                $data[$key]['id'] = $value['id'];
                $data[$key]['transaction_type'] = $value['transaction_type'] == 10 ? 'credit' : 'debit';
                $data[$key]['user_name'] = Yii::$app->userData->formatName($value['user_id']);
                $data[$key]['token'] = $value['token'];
                $data[$key]['remark'] = $value['remark'];
                $data[$key]['created_at'] = Yii::$app->general->format_date_with_time(strtotime(\Yii::$app->time->asTime($value['created_at'])));
            }
            return ['status' => true, 'data' => [
                'total_token' => Yii::$app->user->identity->userData->token,
                'data' => $data,
            ]];
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Nessun dato trovato')];
        }
    }

    public function actionRefer()
    {
        $checkCode = ReferAndEarn::find()->where(['refer_user_id' => Yii::$app->user->identity->id, 'code_used' => 0])->one();
        if (isset($checkCode)) {
            return ['status' => true, 'data' => \Yii::$app->params['frontend_url'] . '?' . $checkCode->code];
        } else {
            $model = new ReferAndEarn();
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $code = substr(str_shuffle($str_result), 0, 6);
            $model->refer_user_id = Yii::$app->user->identity->id;
            $model->code = $code;
            $model->save();
            return ['status' => true, 'data' => \Yii::$app->params['frontend_url'] . '?' . $model->code];
        }
        return ['status' => false];
    }

    public function actionUserResponse()
    {
        $userData = UserData::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        $data = Yii::$app->userData->loginResponse($userData);
        if ($data) {
            return array('status' => true, 'data' => $data);
        }
        return array('status' => false);
    }
    
    
    public function actionEditLanguage() {
        $user = Yii::$app->user->identity;
        $userData = UserData::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        $request = Yii::$app->request;
        // $lang = $request->post('lang');
        $model = new \frontend\models\UserEditLangForm;
        $model->load(Yii::$app->request->post());
        // return array('status' => false, 'data' => $model->lang);
        if ($model->validate()) {
            $userData->lang = $request->post("lang");
            if ($userData->validate() && $userData->save()) {
                $result = $this->generateUserData($user);
                return [
                    'status' => true,
                    'data' => $result,
                    // 'message' => \Yii::t('app', 'Il tuo profilo � stato aggiornato'),
                ];
            } else {
                return array('status' => false, 'message' => Yii::$app->general->error($userData->errors));
            }
        } else {
            return array('status' => false, 'message' => $model->validate());
            return array('status' => false, 'message' => Yii::$app->general->error($model->errors), "data" =>  $request->post('lang'));
        }
    }
    /**
     * @param User $user
     * 
     * 
     * @return Json
     * */ 
    private function generateUserData(User $user) {
        $user_next_level_point = \Yii::$app->userData->getUserLevelPoint($user->userData->level_id + 1);
        $teamData = Yii::$app->team->getTeam($user->userData->team_id);
        $user_next_level_point = \Yii::$app->userData->getUserLevelPoint($user->userData->level_id + 1);
        $user_previous_level_point = \Yii::$app->userData->getUserLevelPoint($user->userData->level_id);
        return  [
            'id' => $user->id,
            'access_token' => $user->access_token,
            'firstname' => $user->userData->first_name,
            'lastname' => $user->userData->last_name,
            'username' => $user->userData->username,
            'phone' => $user->userData->phone,
            'email' => $user->email,
            'status' => $user->status,
            'gender' => Yii::$app->userData->formatGender($user->userData->gender),
            'city' => [
                'label' => !empty($user->userData->city) ? $user->userData->city->name : "",
                'value' => !empty($user->userData->city) ? $user->userData->city->id : "",
            ],
            'country' => [
                'label' => !empty($user->userData->country_id) ? \Yii::$app->general->getValueFromKey($user->userData->country_id) : '',
                'value' => !empty($user->userData->country_id) ? $user->userData->country_id : '',
            ],
            'education' => [
                'label' => !empty($user->userData->education) ? $user->userData->education->name : "",
                'value' => !empty($user->userData->education) ? $user->userData->education->id : "",
            ],
            'job' => [
                'label' => !empty($user->userData->job) ? $user->userData->job->name : "",
                'value' => !empty($user->userData->job) ? $user->userData->job->id : "",
            ],
            'fan' => [
                'label' => !empty($user->userData->fan) ? $user->userData->fan : "",
                'value' => !empty($user->userData->fan) ? $user->userData->fan : "",
            ],
            'team' => \Yii::$app->team->userTeam($user->userData->team_id),
            'league' => \Yii::$app->league->getLeagueById($user->userData->league_id),
            'birth_date' => $user->userData->birth_date,
            'user_image' => Yii::$app->userData->photo($user->id),
            'language' => $user->userData->lang,
            'point' => $user->userData->point,
            'level' => [
                'current_level' => !empty($user->userData->level_id) ? \Yii::$app->userData->getUserLevel($user->userData->level_id) : 1,
                'next_level' => !empty($user->userData->level_id) ? \Yii::$app->userData->getUserLevel($user->userData->level_id + 1) : 1,
                'progress' => !empty($user->userData->level_id) ? \Yii::$app->userData->getLevelProgress($user->userData->point, $user_next_level_point, $user_previous_level_point) : 0,
            ],
            'token' => $user->userData->token,
            'notification_count' => Yii::$app->push->badgeCount($user->id),
        ];
    }
}
