<?php

namespace frontend\models;;

use common\models\User;
use yii\base\Model;

/**
 * User Edit form
 */
class UserEditLangForm extends Model
{
  
    public $lang;

    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'lang', 'in',
                'range' => ['us', 'it', 'fr', 'sp', 'ge', 'ch', 'ar']
            ]
        ];
    }
}
