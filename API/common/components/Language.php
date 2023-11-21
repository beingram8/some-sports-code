<?php
namespace common\components;

use Yii;

class Language extends \yii\web\Request
{

    public function getCurrentLang()
    {
        $l = 'en-US';
        if (!empty($_GET['lang_id'])) {
            $l = $_GET['lang_id'];
        } else if (!empty($_COOKIE['lang_id'])) {
            $l = $_COOKIE['lang_id'];
        }
        return $l;
    }
    public function langImg($l)
    {
        if ($l) {
            $x = explode("-", $l);
            $code = isset($x[1]) ? strtolower($x[1]) : "us";
            $filename = $code . '.png';
            return \Yii::$app->general->img_assets('flags/' . $filename);
        }
        return '#';
    }
    public static function getCurrentLangImg()
    {
        $l = \Yii::$app->lang->getCurrentLang();
        return \Yii::$app->lang->langImg($l);
    }
    public static function getLangList()
    {
        $data = \common\models\Language::find()->select(['name', 'language_id'])
            ->where(['status' => 1])->asArray()->all();
        $list = [];
        foreach ($data as $k => $item) {
            $item['img'] = \Yii::$app->lang->langImg($item['language_id']);
            array_push($list, $item);
        }
        return $list;
    }

    public static function getList()
    {
        $d = \common\models\Language::find()->select(['name', 'language_id'])
            ->where(['status' => 1])->asArray()->all();

        $data = \yii\helpers\ArrayHelper::map($d, 'language_id', 'name');
        return $data;
    }

    public static function getLanguages()
    {
        $d = \common\models\Language::find()->select(['name', 'language'])
            ->where(['status' => 1])->asArray()->all();

        $data = \yii\helpers\ArrayHelper::map($d, 'language', 'name');
        return $data;
    }
}