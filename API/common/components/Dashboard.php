<?php

namespace common\components;

use Yii;

class Dashboard extends \yii\base\Component
{

    public function monthlyUsers($year = "")
    {
        $year = $year ? $year : date('Y');
        $sql = "
        SELECT  MONTHNAME(FROM_UNIXTIME(created_at)) as name,COUNT(*) as y  FROM user  WHERE
        YEAR(FROM_UNIXTIME(created_at)) = " . $year . " GROUP BY  MONTH(FROM_UNIXTIME(`created_at`)), YEAR(FROM_UNIXTIME(created_at))";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $d = [];
        if (isset($data)) {
            foreach ($data as $k => $row) {
                $d[$k] = array('name' => $row['name'], 'y' => (int) $row['y']);
            }
            return $d;
        }
        return [];
    }

}
