<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $body;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'body'], 'required'],
            [['name', 'body'], 'string'],
            [['name'], 'string', 'min' => 3, 'max' => 30],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        return Yii::$app->mailer->compose()
            ->setTo([\Yii::$app->params['admin_email'] => \Yii::$app->name])
            ->setFrom($this->email)
            ->setSubject(' Contact Mail Sent.')
            ->setHtmlBody($this->body)
            ->send();
    }
}
