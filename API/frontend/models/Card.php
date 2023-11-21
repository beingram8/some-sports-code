<?php

namespace frontend\models;

use yii\base\Model;

/**
 * Signup form
 */
class Card extends Model
{
    public $creditCard_number;
    public $creditCard_expirationDate;
    public $creditCard_cvv;
    public function rules()
    {
        return [
            [['creditCard_number', 'creditCard_expirationDate', 'creditCard_cvv'], 'required'],
        ];
    }

}
