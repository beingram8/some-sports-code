<?php
namespace frontend\models;

use yii\base\Model;

class ImageForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $photo;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['photo', 'required'],
            ['photo', 'file', 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Limit is 10MB'],
        ];
    }
}
