<?php

namespace common\models;

use common\models\UserTokenTransaction;
use Yii;

/**
 * This is the model class for table "user_data".
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string|null $photo
 * @property string|null $lang
 * @property int $gender 1= Male, 2 = Female, 3 = Other
 * @property string $birth_date
 * @property int|null $birth_year
 * @property int|null $city_id
 * @property int|null $education_id
 * @property int|null $job_id
 * @property string|null $fiscal_code
 * @property int|null $point
 * @property int|null $token
 *
 * @property NewsComment[] $newsComments
 * @property NewsLike[] $newsLikes
 * @property QuizAnswer[] $quizAnswers
 * @property QuizWinner[] $quizWinners
 * @property SurveyUserSelectedOption[] $surveyUserSelectedOptions
 * @property TeasingRoomComments[] $teasingRoomComments
 * @property TeasingRoomLikes[] $teasingRoomLikes
 * @property TeasingRoomReported[] $teasingRoomReporteds
 * @property User $user
 * @property UserCityList $city
 * @property UserEducationList $education
 * @property UserJoblevelList $job
 * @property UserEuropeanFanPackage[] $userEuropeanFanPackages
 * @property UserPaymentTransaction[] $userPaymentTransactions
 * @property UserSuperFanPackage[] $userSuperFanPackages
 * @property UserTokenTransaction[] $userTokenTransactions
 */
class UserData extends \yii\db\ActiveRecord
{
    public $email;
    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'team_id'], 'required'],
            [['user_id', 'team_id', 'gender', 'birth_year', 'city_id', 'education_id', 'job_id', 'point', 'token'], 'integer'],
            [['photo'], 'string'],
            [['birth_date', 'email', 'stripe_token', 'old_golden_ball', 'fan'], 'safe'],
            [['first_name', 'last_name', 'country_id'], 'string', 'max' => 70],
            [['username'], 'string', 'max' => 50],
            [['lang'], 'string', 'max' => 4],
            [['phone'], 'string', 'min' => 9],
            [['user_id'], 'unique'],
            [['page', 'per_page'], 'integer'],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTeam::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['league_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonLeague::className(), 'targetAttribute' => ['league_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserCityList::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['education_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserEducationList::className(), 'targetAttribute' => ['education_id' => 'id']],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserJoblevelList::className(), 'targetAttribute' => ['job_id' => 'id']],
            ['team_id', 'safe', 'on' => 'account'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'team_id' => Yii::t('app', 'Team ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'username' => Yii::t('app', 'Username'),
            'photo' => Yii::t('app', 'Photo'),
            'lang' => Yii::t('app', 'Lang'),
            'gender' => Yii::t('app', 'Gender'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'birth_year' => Yii::t('app', 'Birth Year'),
            'city_id' => Yii::t('app', 'Select City'),
            'education_id' => Yii::t('app', 'Education ID'),
            'job_id' => Yii::t('app', 'Job ID'),
            'point' => Yii::t('app', 'Point'),
            'token' => Yii::t('app', 'Token'),
            'phone' => Yii::t('app', 'Phone'),
        ];
    }

    public function checkProfile()
    {
        if (!empty($this->photo) && !empty($this->gender) && !empty($this->city_id) && !empty($this->education_id) && !empty($this->job_id) && !empty($this->fan) && !empty($this->country_id)) {
            $checkRecord = UserTokenTransaction::find()->where(['user_id' => $this->user_id, 'is_profile_setup' => 1])->one();
            if (empty($checkRecord)) {
                Yii::$app->token->updateUserToken($this->user_id, Yii::$app->token->getTokenValue('profile_setup_token'));
                $assign_user_token = new UserTokenTransaction();
                $assign_user_token->user_id = $this->user_id;
                $assign_user_token->transaction_type = 10;
                $assign_user_token->token_type_id = Yii::$app->token->getTokenId('profile_setup_token');
                $assign_user_token->token = Yii::$app->token->getTokenValue('profile_setup_token');
                $assign_user_token->created_by = $this->user_id;
                $assign_user_token->remark = 'For updating your profile';
                $assign_user_token->is_profile_setup = 1;
                if ($assign_user_token->save()) {
                    return true;
                }
            }
        }
        return false;

    }

    /**
     * Gets query for [[NewsComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsComments()
    {
        return $this->hasMany(NewsComment::className(), ['user_id' => 'user_id']);
    }

    public function getTeam()
    {
        return $this->hasOne(SeasonTeam::className(), ['id' => 'team_id']);
    }
    public function getStripeToken()
    {
        return $this->hasOne(UserStripeToken::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[NewsLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsLikes()
    {
        return $this->hasMany(NewsLike::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[QuizAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizAnswers()
    {
        return $this->hasMany(QuizAnswer::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[QuizWinners]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizWinners()
    {
        return $this->hasMany(QuizWinner::className(), ['winner_user_id' => 'user_id']);
    }

    /**
     * Gets query for [[SurveyUserSelectedOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyUserSelectedOptions()
    {
        return $this->hasMany(SurveyUserSelectedOption::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[TeasingRoomComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRoomComments()
    {
        return $this->hasMany(TeasingRoomComments::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[TeasingRoomLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRoomLikes()
    {
        return $this->hasMany(TeasingRoomLikes::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[TeasingRoomReporteds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRoomReporteds()
    {
        return $this->hasMany(TeasingRoomReported::className(), ['reported_user_id' => 'user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(UserCityList::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Education]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(UserEducationList::className(), ['id' => 'education_id']);
    }

    /**
     * Gets query for [[Job]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(UserJoblevelList::className(), ['id' => 'job_id']);
    }

    /**
     * Gets query for [[UserEuropeanFanPackages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserEuropeanFanPackages()
    {
        return $this->hasMany(UserEuropeanFanPackage::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[UserPaymentTransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentTransactions()
    {
        return $this->hasMany(UserPaymentTransaction::className(), ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[UserSuperFanPackages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSuperFanPackages()
    {
        return $this->hasMany(UserSuperFanPackage::className(), ['user_id' => 'user_id']);
    }
    public function getUuids()
    {
        return $this->hasMany(UserUuid::className(), ['user_id' => 'user_id']);
    }

    public function getParentConfirmation()
    {
        return $this->hasOne(ParentConfirmation::className(), ['user_id' => 'user_id']);
    }
    /**
     * Gets query for [[UserTokenTransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokenTransactions()
    {
        return $this->hasMany(UserTokenTransaction::className(), ['user_id' => 'user_id']);
    }
    public function getLevel()
    {
        return $this->hasOne(UserLevelList::className(), ['id' => 'level_id']);
    }
    public function getUserRanking()
    {
        $query = \common\models\UserData::find()
            ->joinWith(['team'])
            ->leftJoin('auth_assignment', 'user_data.user_id = auth_assignment.user_id')
            ->where(['auth_assignment.item_name' => 'user'])
            ->orderBy(['point' => SORT_DESC])
            ->limit(20)
            ->asArray()
            ->all();

        $data = [];
        $i = 0;
        foreach ($query as $key => $value) {
            $data[$key]['name'] = $value['username'];
            $data[$key]['user_photo'] = Yii::$app->userData->photo($value['user_id']);
            $data[$key]['points'] = $value['point'];
            $data[$key]['user_team_name'] = !empty($value['team']) ? $value['team']['name'] : '';
            $data[$key]['team_photo'] = !empty($value['team']) ? $value['team']['logo'] : '';
            $data[$key]['rank'] = $i + 1;
            $i++;
        }

        $myPosition = [];
        if (!empty(\Yii::$app->user->identity->id)) {
            $rankList = \common\models\UserData::find()
                ->joinWith(['team'])
                ->leftJoin('auth_assignment', 'user_data.user_id = auth_assignment.user_id')
                ->where(['auth_assignment.item_name' => 'user'])
                ->orderBy(['point' => SORT_DESC])
                ->asArray()
                ->all();

            $test = \yii\helpers\ArrayHelper::getColumn($rankList, 'user_id');

            $position = \common\models\UserData::find()
                ->joinWith(['team'])
                ->leftJoin('auth_assignment', 'user_data.user_id = auth_assignment.user_id')
                ->where(['auth_assignment.item_name' => 'user'])
                ->andWhere(['user_data.user_id' => \Yii::$app->user->identity->id])
                ->asArray()
                ->one();

            $myPosition = [
                'rank' => array_search($position['user_id'], $test) + 1,
                'name' => $position['username'],
                'user_photo' => Yii::$app->userData->photo($position['user_id']),
                'points' => $position['point'],
                'user_team_name' => !empty($position['team']) ? $position['team']['name'] : '',
                'team_photo' => !empty($position['team']) ? $position['team']['logo'] : '',
            ];
        }
        {
            return array('user_position' => $myPosition, 'rows' => $data);
        }
    }
}
