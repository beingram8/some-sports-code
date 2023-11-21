<?php
namespace frontend\models;

use common\models\SeasonTeam;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $firstname;
    public $lastname;
    public $email;
    public $username;
    public $password;
    public $birth_date;
    public $gender;
    public $team_id;
    public $league_id;
    public $refer_code;
    public $provider_type;
    public $provider_key;
    public $platform;
    public $uuid;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [['username', 'email'], 'required'],
            ['password', 'required', 'on' => ['create']],
            // [['firstname', 'lastname', 'username'], 'string', 'length' => [0, 25]],
            [['password'], 'string', 'length' => [8, 50]],
            ['email', 'email'],
            [['provider_type', 'provider_key', 'platform', 'uuid'], 'string'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' =>'This email address has already been taken.',
            ],
            ['league_id', 'exist', 'targetClass' => '\common\models\SeasonLeague', 'message' => 'Invalid League.', 'targetAttribute' => 'id'],
            ['team_id', 'exist', 'targetClass' => '\common\models\SeasonTeam', 'message' => 'Invalid Team.', 'targetAttribute' => 'id'],

            [
                'username',
                'unique',
                'targetClass' => '\common\models\UserData',
                'message' => 'This username has already been taken.',
            ],
            ['email', 'string', 'length' => [5, 200]],
            ['refer_code', 'safe'],
            //['refer_code', 'exist', 'targetClass' => '\common\models\ReferAndEarn', 'message' => 'Invalid Refer Code.', 'targetAttribute' => 'code'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }
        $user = new \common\models\User();
        $userData = new \common\models\UserData();

        $user->email = $this->email;
        if ($this->provider_type && $this->provider_key) {
            $user->is_social = 1;
            $user->status = 10;
        } else {
            $user->is_social = 0;
            $user->status = 10;
            $user->setPassword($this->password);
        }

        $user->generateAuthKey();
        if(empty($this->firstname)){
            $this->firstname = "";
        }
        if(empty($this->lastname)){
            $this->lastname = "";
        }
        if(empty($this->birth_date)){
            $this->birth_date = "1970-01-01";
        }
        if(empty($this->gender)){
            $this->gender = 3;
        }


        $userData->first_name = $this->firstname;
        $userData->username = $this->username;
        $userData->last_name = $this->lastname;
        $userData->birth_date = $this->birth_date;
        $userData->birth_year = date_format(date_create($this->birth_date), 'Y');
        $userData->gender = $this->gender;
        $userData->team_id = $this->team_id;
        $userData->league_id = $this->league_id;
        $user->generateEmailVerificationToken();

        if (!empty($this->refer_code)) {
            $checkCode = \common\models\ReferAndEarn::find()->where(['code' => $this->refer_code, 'code_used' => 0])->one();
        }
        // \Yii::$app->general->throwError(\Yii::t('app', $userData->username));
        if ($user->validate() && $user->save()) {
            $userData->user_id = $user->id;
            if ($userData->validate() && $userData->save()
                && \common\models\User::roleAssignment($user->id, 'user')
                && $this->sendEmail($userData, $user)) {
                if (!empty($checkCode)) {
                    $model = new \common\models\UserTokenTransaction();
                    $model->user_id = $checkCode->refer_user_id;
                    $model->transaction_type = 10;
                    $model->token = \Yii::$app->token->getTokenValue('refer_token');
                    $model->token_type_id = \Yii::$app->token->getTokenId('refer_token');
                    $model->created_by = $user->id;
                   $model->remark = 'For inviting a friend on Fan Rating';
                    if ($model->save() && Yii::$app->token->updateUserToken($model->user_id, $model->token)) {
                        $checkCode->code_used = 1;
                        $checkCode->save(false);
                    }
                }
                Yii::$app->token->updateUserToken($user->id, Yii::$app->token->getTokenValue('welcome_token'));
                Yii::$app->token->userTokenTransaction($user->id);
                return true;
            } else {
                \Yii::$app->general->throwError(\Yii::t('app', 'User data not saved.'));
            }
        } else {
            return false;
        }
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($userData, $user)
    {
        if ($user->is_social == 0) {
            $subject = Yii::$app->emailtemplate->replace_string_email(["{{app_name}}" => \Yii::$app->params['app_name']], "account_verification", "subject"); // $string_array = Array Of String welcome_mail = Email Slug and subject
            $string_array = array(
                "{{link}}" => \Yii::$app->params['frontend_url'] . '/verification?' . $user->verification_token,
            );
            $html = \Yii::$app->emailtemplate->replace_string_email($string_array, "account_verification");
            return \Yii::$app
                ->mailer->compose()
                ->setHtmlBody($html)
                ->setFrom([\Yii::$app->params['sender_email'] => \Yii::$app->params['app_name']])
                ->setTo($user->email)
                ->setSubject($subject)
                ->send();
        } else {

            return true;
        }
    }
}
