<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'length' => [6, 15]],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword($token)
    {
        $this->_user = \common\models\User::findByPasswordResetToken($token);
        if (!$this->_user) {
            return $this->addError('password', 'No user found with this token.');
        }
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}
