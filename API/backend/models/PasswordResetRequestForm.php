<?php
namespace backend\models;

use common\models\Employee;
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
                'targetClass' => '\common\models\Employee',
                'filter' => ['status' => Employee::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.',
            ],
        ];
    }

    public function sendEmail()
    {

        /* @var $user User */
        $user = Employee::find()->where([
            'AND',
            [
                '!=',
                'role',
                'user',
            ],
            [
                'status' => Employee::STATUS_ACTIVE,
                'email' => $this->email,
            ],
        ])->one();
        if (!$user) {
            return false;
        }

        if (!Employee::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([\Yii::$app->params['admin_email'] => \Yii::$app->params['app_name']])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->params['app_name'])
            ->send();
    }
}