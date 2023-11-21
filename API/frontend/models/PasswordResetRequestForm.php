<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
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
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => \Yii::t('app','Nessun utente con questo indirizzo email.'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function rand_string($length)
    {
        $str = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }

        return $str;
    }
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::find()->joinWith(['authAssignment'])
            ->where(['item_name' => 'user'])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->andWhere(['email' => $this->email])
            ->one();
        if (!$user) {
            return false;
        }
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $subject = Yii::$app->emailtemplate->replace_string_email(["{{app_name}}" => \Yii::$app->params['app_name']], "forgot_password", "subject"); // $string_array = Array Of String welcome_mail = Email Slug and subject
        $string_array = array(
            "{{name}}" => $user->userData->first_name . ' ' . $user->userData->last_name,
            "{{link}}" => \Yii::$app->params['frontend_url'] . '/reset-password?' . $user->password_reset_token,
        );
        $html = \Yii::$app->emailtemplate->replace_string_email($string_array, "forgot_password");
        return \Yii::$app
            ->mailer->compose()
            ->setHtmlBody($html)
            ->setFrom([\Yii::$app->params['sender_email'] => \Yii::$app->params['app_name']])
            ->setTo($user->email)
            ->setSubject($subject)
            ->send();
    }
}