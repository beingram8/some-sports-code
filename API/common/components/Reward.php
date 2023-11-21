<?php

namespace common\components;

use Yii;

class Reward extends \yii\base\Component
{
    public function getCategoryName($id)
    {
        $data = \common\models\RewardCategory::findOne($id);
        return $data->name;
    }
    public function getRewardName($id)
    {
        $data = \common\models\RewardProduct::findOne($id);
        return $data->name;
    }
    public function allCategory()
    {
        $query = \common\models\RewardCategory::find()->asArray()->all();
        return \yii\helpers\ArrayHelper::map($query, 'id', 'name');
    }
    public function getTokenValue($id)
    {
        $token_type = \common\models\TokenType::findOne($id);
        return !empty($token_type) ? $token_type->value : 0;
    }
}