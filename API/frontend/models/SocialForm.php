<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SocialForm extends Model
{
    public $social_type;
    public $uuid;
    public $platform;
    public $token;
    public $name;
    public $email;

    public function rules()
    {
        return [
            [['social_type', 'uuid', 'platform', 'token'], 'required'],
            [['uuid', 'token'], 'string'],

            ['name', 'required', 'on' => 'email_setup'],
            ['name', 'string', 'length' => [3, 25], 'on' => 'email_setup'],

            ['email', 'required', 'on' => 'email_setup'],
            ['email', 'email', 'on' => 'email_setup'],
            ['email', 'string', 'max' => 255, 'on' => 'email_setup'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => \Yii::t('model', 'This email address has already been taken.'),
                'on' => 'email_setup',
            ],
        ];
    }
}