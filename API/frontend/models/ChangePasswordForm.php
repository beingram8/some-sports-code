<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $password;
    public $cpassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'cpassword'], 'required'],
            ['password', 'string', 'length' => [6, 15]],
            ['cpassword', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => \Yii::t("app", "Passwords don't match")],
        ];
    }
    public function attributeLabels()
    {
        return [
            'cpassword' => Yii::t('app', 'Confirm Password'),
            'password' => Yii::t('app', 'Password'),
        ];
    }
    public function update()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = \common\models\User::findOne(\Yii::$app->user->id);
        $user->setPassword($this->password);
        return $user->save();
    }
}
