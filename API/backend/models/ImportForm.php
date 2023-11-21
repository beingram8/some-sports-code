<?php
namespace app\models;

use yii\base\Model;

class ImportForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['file', 'required'],
            [['file'], 'file', 'extensions' => 'csv', 'maxSize' => 1024 * 1024 * 5],
        ];
    }
}