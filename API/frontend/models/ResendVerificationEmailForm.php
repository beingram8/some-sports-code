<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Employee',
                'filter' => ['status' => Employee::STATUS_INACTIVE],
                'message' => 'There is no user with this email address.',
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = \common\models\User::findOne([
            'email' => $this->email,
            'status' => 9,
        ]);

        if ($user === null) {
            return false;
        }

        $subject = Yii::$app->emailtemplate->replace_string_email(["{{app_name}}" => \Yii::$app->params['app_name']], "account_verification", "subject"); // $string_array = Array Of String welcome_mail = Email Slug and subject
        $string_array = array(
            "{{name}}" => $user->userData->firstname . ' ' . $user->userData->lastname,
            "{{link}}" => \Yii::$app->params['frontend_url'] . '/Verification?' . $user->verification_token,
        );
        $html = \Yii::$app->emailtemplate->replace_string_email($string_array, "account_verification");
        return \Yii::$app
            ->mailer->compose()
            ->setHtmlBody($html)
            ->setFrom([\Yii::$app->params['sender_email'] => \Yii::$app->params['app_name']])
            ->setTo($user->email)
            ->setSubject($subject)
            ->send();
    }
}