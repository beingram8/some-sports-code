<?php

namespace common\components;

use Yii;

class Notification extends \yii\base\Component
{
    public function runFcm($title, $msg, $uuids, $group_key, $data)
    {
        $msg_ = $msg;
        $msg = array(
            'message' => $msg,
            'title' => $title,
            'body' => $msg,
            "sound" => "notifsound.mp3",
            'vibrate' => array(1000, 1000, 1000, 1000, 1000),
            'vibration' => 1000,
        );
        $fields = array
            (
            'registration_ids' => $uuids,
            'notification' => $msg,
            'data' => array(
                'title' => $title,
                'msg' => $msg_,
                "image" => 'www/res/android/48x48.png',
            ),
            'priority' => 'high',
            'vibrate' => array(1000, 1000, 1000, 1000, 1000),
            'vibration' => 1000,
            'content_available' => true,
            'show_in_foreground' => true,
        );
        $headers = array
            (
            'Authorization: key=' . Yii::$app->params['push_api_key'],
            'Content-Type: application/json',
        );
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $res = curl_exec($ch);
        $data['title'] = $title;
        $data['msg_'] = $msg_;
        $data['group_key'] = $group_key;
        $data['res'] = $res;
        Yii::info($res, 'Notification');
        return $res;
    }
    public function sendPush()
    {
        $one_hour_ago = time() - 3 * 3600;
        $notification = \common\models\Notification::find()
            ->select(['group_key', 'message', 'title'])
            ->where(['push_completed' => 0])
            ->andWhere(['>=', 'created_at', $one_hour_ago])->one();

        if (isset($notification['group_key'])) {

            $group_key = $notification['group_key'];
            $data = json_decode($notification['data'], true);
            $title = $notification['title'];
            $message = $notification['message'];
            $_page = 1;
            $per_page = 500;
            $is_more = true;
            while ($is_more) {
                $one_hour_ago = time() - 3 * 3600;
                $query = \common\models\Notification::find()
                    ->select(['uuid', 'id'])
                    ->where(['group_key' => $group_key, 'push_completed' => 0])
                    ->andWhere(['>=', 'created_at', $one_hour_ago])
                    ->orderBy('id DESC');
                $page = $_page > 0 ? ($_page - 1) : 0;
                $provider = new \yii\data\ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'forcePageParam' => true,
                        'page' => $page,
                        'pageParam' => 'page',
                        'defaultPageSize' => $per_page,
                        'pageSizeLimit' => [10, 50, 100],
                        'pageSizeParam' => 'per_page',
                        'validatePage' => true,
                        'params' => '',
                    ],
                ]);
                $notifications = $provider->getModels();
                $pagination = array_intersect_key(
                    (array) $provider->pagination,
                    array_flip(
                        $paginationParams = [
                            'pageParam',
                            'pageSizeParam',
                            'params',
                            'totalCount',
                            'defaultPageSize',
                            'pageSizeLimit',
                        ]
                    )
                );
                $totalPage = $pagination['totalCount'] / $per_page;
                $uuids = [];
                $notification_ids = [];
                foreach ($notifications as $key => $notification) {
                    $uuids = array_merge($uuids, json_decode($notification->uuid, true));
                    array_push($notification_ids, $notification->id);
                }
                \common\models\Notification::updateAll(['push_completed' => 1],
                    ['IN', 'id', $notification_ids]);
                if ($totalPage <= $_page) {
                    $is_more = false;
                } else {
                    $is_more = true;
                }
                if (!empty($uuids) && (count($uuids) > 990 || $is_more == false)) {
                    $this->runFcm($title, $message, $uuids, $group_key, $data);
                }
                $_page++;
            }

        }
        return true;
    }
    public function savePush($title, $message, $type, $data, $group_key, $per_page = 1000)
    {
        $is_more = true;
        $_page = 1;

        while ($is_more) {
            $query = \common\models\UserData::find()
                ->join('INNER JOIN', 'user_uuid', 'user_uuid.user_id=user_data.user_id')
                ->orderBy('user_data.user_id DESC')
                ->groupBy('user_uuid.user_id');
            $page = $_page > 0 ? ($_page - 1) : 0;
            $provider = new \yii\data\ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => true,
                    'page' => $page,
                    'pageParam' => 'page',
                    'defaultPageSize' => $per_page,
                    'pageSizeLimit' => [10, 50, 100],
                    'pageSizeParam' => 'per_page',
                    'validatePage' => true,
                    'params' => '',
                ],
            ]);
            $users = $provider->getModels();

            $pagination = array_intersect_key(
                (array) $provider->pagination,
                array_flip(
                    $paginationParams = [
                        'pageParam',
                        'pageSizeParam',
                        'params',
                        'totalCount',
                        'defaultPageSize',
                        'pageSizeLimit',
                    ]
                )
            );
            $totalPage = $pagination['totalCount'] / $per_page;
            $batches = [];
            foreach ($users as $key => $user) {
                $uuids = [];
                foreach ($user->uuids as $d) {
                    array_push($uuids, $d->uuid);
                }
                array_push(
                    $batches,
                    [$user->user_id, json_encode($uuids), $title, $message, $type, ($data), $group_key, time()]
                );
            }
            $attribute = ['user_id', 'uuid', 'title', 'message', 'type', 'data', 'group_key', 'created_at'];
            Yii::$app->db->createCommand()->batchInsert('notification', $attribute, $batches)->execute();
            if ($totalPage <= $_page) {
                $is_more = false;
            } else {
                $is_more = true;
            }
            $_page++;
        }
        return true;
    }
    public function savePushByAdmin($title, $message, $type, $data, $group_key, $team_ids = [], $user_ids = [])
    {

        $is_more = true;
        $_page = 1;
        $per_page = 1000;
        while ($is_more) {
            $query = \common\models\UserData::find()
                ->join('INNER JOIN', 'user_uuid', 'user_uuid.user_id=user_data.user_id')
                ->where(1)
                ->orderBy('user_data.user_id DESC');
            if (!empty($team_ids)) {
                $query->andWhere(['IN', 'team_id', $team_ids]);
            }
            if (!empty($user_ids)) {
                $query->andWhere(['IN', 'user_uuid.user_id', $user_ids]);
            }
            $page = $_page > 0 ? ($_page - 1) : 0;
            $provider = new \yii\data\ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => true,
                    'page' => $page,
                    'pageParam' => 'page',
                    'defaultPageSize' => $per_page,
                    'pageSizeLimit' => [10, 50, 100],
                    'pageSizeParam' => 'per_page',
                    'validatePage' => true,
                    'params' => '',
                ],
            ]);
            $users = $provider->getModels();
            $pagination = array_intersect_key(
                (array) $provider->pagination,
                array_flip(
                    $paginationParams = [
                        'pageParam',
                        'pageSizeParam',
                        'params',
                        'totalCount',
                        'defaultPageSize',
                        'pageSizeLimit',
                    ]
                )
            );
            $totalPage = $pagination['totalCount'] / $per_page;
            $batches = [];
            foreach ($users as $key => $user) {
                $uuids = [];
                foreach ($user->uuids as $d) {
                    array_push($uuids, $d->uuid);
                }
                array_push(
                    $batches,
                    [$user->user_id, json_encode($uuids), $title, $message, $type, ($data), $group_key, time()]
                );
            }
            $attribute = ['user_id', 'uuid', 'title', 'message', 'type', 'data', 'group_key', 'created_at'];
            Yii::$app->db->createCommand()->batchInsert('notification', $attribute, $batches)->execute();
            if ($totalPage <= $_page) {
                $is_more = false;
            } else {
                $is_more = true;
            }
            $_page++;
        }
        return true;
    }
    public function message($type)
    {
        if ($type == "vote_is_open") {
            $message = "Voting is open for the Milan-Bevento";
        } else if ($type == "vote_is_closing_6_hour_ago") {
            $message = "Voting for the match will close in 6 hours Milan-Bevento";
        } else if ($type == "new_event") {
            $message = "{news_title} any text you want to add";
        } else if ($type == "quiz_is_online") {
            $message = "The quiz is online to participate and earn tokens {token}";
        } else if ($type == "survey_is_online") {
            $message = "The survey is online to participate and earn tokens {token}";
        } else if ($type == "video_is_uploaded") {
            $message = "The new video has been uploaded";
        } else if ($type == "streaming_is_live") {
            $message = "The stream is live";
        } else if ($type == "match_winner") {
            $message = "You are the winner of the match your score is {score} per match of {match}";
        } else if ($type == "comment") {
            $message = "{user} comment on your post";

        } else if ($type == "like") {
            $message = "{{user}} I like your post";
        }
    }

}
