<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class EditProfileForm extends Model
{
    public $id;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $phone_code;
    public $country;
    public $province;
    public $timezone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            ['id', 'integer'],
            [['name', 'surname', 'email', 'phone', 'phone_code', 'country', 'province', 'timezone'], 'required'],

            [['name', 'surname'], 'string', 'length' => [3, 25]],

            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'filter' => ['!=', 'id', \Yii::$app->user->id],
                'message' => \Yii::t('app', 'This email address has already been taken.'),
            ],

            ['email', 'string', 'length' => [3, 200]],

            ['phone', 'string', 'length' => [9, 15]],
            [
                'phone',
                'unique',
                'filter' => ['!=', 'id', \Yii::$app->user->id],
                'targetClass' => '\common\models\User',
                'message' => \Yii::t('app', 'This phone no has already been taken.'),
            ],
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = \common\models\Employee::findOne(\Yii::$app->user->id);
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->phone = $this->phone;
        $user->phone_code = $this->phone_code;
        $user->email = $this->email;
        $user->country_id = $this->country;
        $user->province = $this->province;
        $user->timezone = $this->timezone;

        return $user->save();

    }

}