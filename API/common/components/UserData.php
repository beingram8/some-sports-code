<?php
namespace common\components;

use Yii;

class UserData extends \yii\base\Component
{
    public function getLevelPrice($level_id)
    {
        $level = \common\models\UserLevelList::find()->where(['id' => $level_id])->one();
        if ($level) {
            return $level;
        } else {
            return false;
        }
    }

    public function getUserTeam($user_id)
    {
        $user = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        if ($user) {
            return [
                'team_name' => $user->team->name,
                'team_logo' => $user->team->logo,
            ];
        } else {
            return "-";
        }
    }

    public function getSocialData($token, $social_type)
    {
        $provider_id = "";
        $email = "";
        $name = "";
        if ($social_type == 1) {
            $user_details = "https://graph.facebook.com/me?access_token=" . $token . '&fields=email,name';
            try {
                $response = file_get_contents($user_details);
                $response = json_decode($response);
                if (!empty($response)) {
                    $provider_id = $response->id;
                    $email = !empty($response->email) ? $response->email : "";
                    $name = !empty($response->name) ? $response->name : $response->id;
                }
            } catch (\Exception $e) {
            }
        } else if ($social_type == 2) {
            try {
                $user_details = 'https://www.googleapis.com/oauth2/v3/tokeninfo?access_token=' . urlencode($token);
                $response = file_get_contents($user_details);
                $response = json_decode($response);
                if (!empty($response) && !empty($response->email)) {
                    $arr = explode("@", $response->email);
                    $provider_id = $response->sub;
                    $email = $response->email;
                    $name = $arr[0];
                }
            } catch (\Exception $e) {
            }
        } else if ($social_type == 3) {
            try {
                //If the exception is thrown, this text will not be shown
                $appleSignInPayload = \AppleSignIn\ASDecoder::getAppleSignInPayload($token);
                if (!empty($appleSignInPayload)) {
                    $email = $appleSignInPayload->getEmail();
                    $user = $appleSignInPayload->getUser();
                    $provider_id = $user;
                    if (!empty($email)) {
                        $arr = explode("@", $email);
                        $name = !empty($arr[0]) ? $arr[0] : '-';
                        $provider_id = $user;
                    }
                }
            } catch (\Exception $e) {
            }
        }
        return [
            'provider_type' => $social_type,
            'provider_key' => $provider_id,
            'email' => $email,
            'first_name' => $name,
        ];
    }
    public function recordUserSystemOS()
    {
        $user = \common\models\Employee::findOne(\Yii::$app->user->id);
        if ($user) {
            $user->system_os = \Yii::$app->system->getOS();
            $user->system_browser = \Yii::$app->system->getBrowser();
            $user->save(false);
        }
        return true;
    }

    public function formatName($user_id)
    {
        $userData = \common\models\UserData::find()->select(['first_name', 'last_name'])->where(['user_id' => $user_id])->one();
        return !empty($userData->first_name) && !empty($userData->last_name) ? $userData->first_name . ' ' . $userData->last_name : '-';
    }

    public function formatGender($gender)
    {
        if ($gender == 1) {
            return 'Male';
        } else if ($gender == 2) {
            return 'Female';
        } else {
            return 'Other';
        }
    }

    public function username($user_id)
    {
        $userData = \common\models\UserData::find()->select(['username'])->where(['user_id' => $user_id])->one();
        return !empty($userData->username) ? $userData->username : '-';
    }

    public function photo($user_id)
    {
        $user = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        if (!empty($user->photo)) {
            return $user->photo;
        }
        return \Yii::$app->general->img_assets('placeholder_for_user.svg');
    }
    public function roles($role_for = "")
    {
        $rr = [];
        $d = \Yii::$app->authManager->getRoles();
        foreach ($d as $role => $r) {
            $rr[$role] = $r->description;
        }
        return $rr;
    }
    public function role($user_id, $formatted = false)
    {
        $d = \Yii::$app->authManager->getRolesByUser($user_id);
        if ($d) {
            $d = array_pop($d);

            return $formatted ? ucwords($d->name) : $d->name;
        }
        return;
    }
    public function getLevelList()
    {
        $levelData = \common\models\UserLevelList::find()->where(1)->asArray()->all();
        return \yii\helpers\ArrayHelper::map($levelData, 'id', 'level');
    }
    public function getLevelbyPoint($point)
    {
        $levelData = \common\models\UserLevelList::find()->where(['<=', 'point', $point])->orderBy(['id' => SORT_DESC])->one();
        if (!empty($levelData)) {
            return $levelData->id;
        }
        return 1;
    }
    public function getUserLevel($id)
    {
        //$id = $id == 13 ? 12 : $id;
        $levelData = \common\models\UserLevelList::find()->where(['id' => $id])->one();
        if (!empty($levelData)) {
            return $levelData->level;
        }
        return '-';
    }

    public function getUserLevelPoint($id)
    {
        $levelData = \common\models\UserLevelList::find()->where(['id' => $id])->one();
        if (!empty($levelData)) {
            return $levelData->point;
        }
        return 1;
    }

    public function getLevelProgress($user_point, $next_level_point, $previous_level_point)
    {
        $point = $next_level_point - $previous_level_point;
        $user_point = $user_point - $previous_level_point;
        return number_format($user_point * 100 / $point, 2);
    }

    public function totalPoint($user_id)
    {
        $user_data = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        return $user_data->point;
    }
    public function totalToken($user_id)
    {
        $user_data = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
        return $user_data->token;
    }
    public function sum_of_point($user_id)
    {
        $points = \common\models\UserPointTransaction::find()->where(['user_id' => $user_id])->sum('points');
        if (isset($points)) {
            $level_id = $this->getLevelbyPoint($points);
            $user_data = \common\models\UserData::find()->where(['user_id' => $user_id])->one();
            \common\models\UserData::updateAll(['point' => $points, 'level_id' => $level_id], ['user_id' => $user_id]);

        }
        return false;
    }

    public function sum_of_token($user_id)
    {
        $tokens = \common\models\UserTokenTransaction::find()->where(['user_id' => $user_id])->sum('token');
        if (isset($tokens)) {
            \common\models\UserData::updateAll(['token' => $tokens], ['user_id' => $user_id]);
        }
        return false;
    }

    public function loginResponse($userData)
    {
        if (!empty($userData)) {
            $userData->user->generateAccessTokenAfterUpdatingClientInfo(true);
            $teamData = Yii::$app->team->getTeam($userData->team_id);
            $user_next_level_point = \Yii::$app->userData->getUserLevelPoint($userData->level_id + 1);
            $user_previous_level_point = \Yii::$app->userData->getUserLevelPoint($userData->level_id);
            return array(
                'id' => $userData->user_id,
                'access_token' => $userData->user->access_token,
                'firstname' => $userData->first_name,
                'lastname' => $userData->last_name,
                'username' => $userData->username,
                'email' => $userData->user->email,
                'status' => $userData->user->status,
                'gender' => Yii::$app->userData->formatGender($userData->gender),
                'country' => [
                    'label' => !empty($userData->country_id) ? \Yii::$app->general->getValueFromKey($userData->country_id) : '',
                    'value' => !empty($userData->country_id) ? $userData->country_id : '',
                ],
                'city' => [
                    'label' => !empty($userData->city->name) ? $userData->city->name : '',
                    'value' => !empty($userData->city_id) ? $userData->city_id : '',
                ],
                'education' => [
                    'label' => !empty($userData->education->name) ? $userData->education->name : '',
                    'value' => !empty($userData->education_id) ? $userData->education_id : '',
                ],
                'job' => [
                    'label' => !empty($userData->job->name) ? $userData->job->name : '',
                    'value' => !empty($userData->job_id) ? $userData->job_id : '',
                ],
                'fan' => [
                    'label' => !empty($userData->fan) ? $userData->fan : "",
                    'value' => !empty($userData->fan) ? $userData->fan : "",
                ],
                'level' => [
                    'current_level' => !empty($userData->level_id) ? \Yii::$app->userData->getUserLevel($userData->level_id) : 1,
                    'next_level' => !empty($userData->level_id) ? \Yii::$app->userData->getUserLevel($userData->level_id + 1) : 1,
                    'progress' => !empty($userData->level_id) ? \Yii::$app->userData->getLevelProgress($userData->point, $user_next_level_point, $user_previous_level_point) : 0,
                ],
                'phone' => $userData->phone,
                'team' => \Yii::$app->team->userTeam($userData->team_id),
                'league' => \Yii::$app->league->getLeagueById($userData->league_id),
                'birth_date' => $userData->birth_date,
                'user_image' => Yii::$app->userData->photo($userData->user_id),
                'language' => $userData->lang,
                'point' => $userData->point,
                'token' => $userData->token,
                'notification_count' => Yii::$app->push->badgeCount($userData->user_id),
            );
        }
        return false;
    }
}
