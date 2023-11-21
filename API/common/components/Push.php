<?php

namespace common\components;

use common\models\Notification;
use common\models\UserUuid;
use Yii;
use yii\base\Component;

class Push extends Component
{
    public function getUuid($UserId = "")
    {
        $Data = UserUuid::find()->where(['user_id' => $UserId])->asArray()->all();
        $UUIDS = array();
        if (!empty($Data)) {
            foreach ($Data as $uuid) {
                array_push($UUIDS, $uuid['uuid']);
            }
        }
        return $UUIDS;
    }

    public function badgeCount($UserId)
    {
        return Notification::find()->where(['user_id' => $UserId, 'is_read' => 'N'])->count();
    }

    public function batch_of_1000_uuid($registrationIds, $title, $msg)
    {
        $uuid_batch_of_999 = !empty($registrationIds) ? array_chunk($registrationIds, 999) : [];
        foreach ($uuid_batch_of_999 as $uuid_9999) {
            $this->run_fcm($title, $msg, $uuid_9999);
        }
    }

    public function run_fcm($title, $msg, $uuids)
    {
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
                'message' => $msg,
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
            'Authorization: key=' . \Yii::$app->params['push_api_key'],
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
        return $res;
    }

    public function savePush($input)
    {
        $app_type = !empty($input['app_type']) ? $input['app_type'] : "User";
        $Notification = new Notification;
        $Notification->user_id = !empty($input['user_id']) ? $input['user_id'] : "";
        $Notification->from_user_id = !empty($input['from_user_id']) ? $input['from_user_id'] : "";
        $Notification->uuid = json_encode($this->getUuid($Notification->user_id));
        $Notification->type = !empty($input['type']) ? $input['type'] : "";
        $Notification->title = !empty($input['title']) ? $input['title'] : "";
        $Notification->message = !empty($input['message']) ? $input['message'] : "";
        $Notification->badge_count = $this->badgeCount($Notification->user_id);
        $Notification->push_completed = 0;
        $Notification->save();
        return true;
    }
    public function sendPush($Notification)
    {
        if ($Notification->push_completed) {
            return true;
        } else {
            $uuid = json_decode($Notification->uuid);
            if (count($uuid) > 0) {
                $msg = array(
                    'message' => $Notification->message,
                    'title' => $Notification->title,
                    'body' => $Notification->message,
                    "sound" => "notifsound.mp3",
                    'vibrate' => array(1000, 1000, 1000, 1000, 1000),
                    'vibration' => 1000,
                    'badge' => (int) $Notification->badge_count + 1,
                );
                $time = $Notification->created_at;
                $fields = array(
                    'registration_ids' => $uuid,
                    'notification' => $msg,
                    'data' => array(
                        'title' => $Notification->title,
                        'message' => $Notification->message,
                        'id' => $Notification->id,
                        'type' => $Notification->type,
                        'is_read' => $Notification->is_read,
                        'created_at' => $time,
                        "image" => 'www/res/android/48x48.png',
                        'badge' => $this->badgeCount($Notification->user_id),
                    ),
                    'priority' => 'high',
                    'vibrate' => array(1000, 1000, 1000, 1000, 1000),
                    'vibration' => 1000,
                    'content_available' => true,
                    'show_in_foreground' => true,
                    'badge' => (int) $Notification->badge_count + 1,
                );
                $headers = array(
                    'Authorization: key=' . Yii::$app->params['push_api_key'],
                    'Content-Type: application/json',
                );
                $ch = curl_init("https://fcm.googleapis.com/fcm/send");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $res = curl_exec($ch);
                $Notification->push_request = json_encode($fields);
                $Notification->push_response = $res;
            } else {
                $Notification->push_request = 'UUID is not available or Notification status off';
                $Notification->push_response = 'UUID is not available or Notification status off';
            }
            $Notification->push_completed = 1;
            if ($Notification->save()) {
                return true;
            } else {
                return false;
            }
        }
    }

}