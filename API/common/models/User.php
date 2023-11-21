<?php

namespace common\models;

use Firebase\JWT\JWT;
use Yii;
use yii\web\IdentityInterface;
use yii\web\Request as WebRequest;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property int $is_social
 * @property string|null $password_hash Password
 * @property string|null $password_reset_token
 * @property int $status 0=Deleted,10= Active,9=Inactive
 * @property string|null $verification_token
 * @property string|null $access_token
 * @property int|null $access_token_expired_at
 * @property string|null $auth_key
 * @property int|null $created_at
 *
 * @property TeasingRoom[] $teasingRooms
 * @property UserData $userData
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    protected static $decodedToken;
    /** @var  string to store JSON web token */
    /** @var  array $permissions to store list of permissions */
    public $permissions;
    public $authKey;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['is_social', 'status', 'access_token_expired_at', 'created_at'], 'integer'],
            [['access_token'], 'string'],
            [['email'], 'string', 'max' => 100],
            [['password_hash', 'password_reset_token', 'verification_token', 'auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'is_social' => Yii::t('app', 'Is Social'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'status' => Yii::t('app', 'Status'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'access_token' => Yii::t('app', 'Access Token'),
            'access_token_expired_at' => Yii::t('app', 'Access Token Expired At'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[TeasingRooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeasingRooms()
    {
        return $this->hasMany(TeasingRoom::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserData()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'id']);
    }

    public function getUserSocialData()
    {
        return $this->hasOne(UserSocialAuth::className(), ['user_id' => 'id']);
    }

    public static function roleAssignment($user_id, $role)
    {
        \Yii::$app->db->createCommand('DELETE FROM `auth_assignment` WHERE `user_id` = ' . $user_id)->execute();
        $authManager = Yii::$app->authManager;
        $authItem = $authManager->getRole($role);
        if ($authManager->assign($authItem, $user_id)) {
            return true;
        }
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

        $secret = static::getSecretKey();

        // Decode token and transform it into array.
        // Firebase\JWT\JWT throws exception if token can not be decoded
        try {
            $decoded = JWT::decode($token, $secret, [static::getAlgo()]);

        } catch (\Exception $e) {
            return false;
        }
        static::$decodedToken = (array) $decoded;
        // If there's no jti param - exception
        if (!isset(static::$decodedToken['data'])) {
            return false;
        }
        // JTI is unique identifier of user.
        // For more details: https://tools.ietf.org/html/rfc7519#section-4.1.7
        $data = static::$decodedToken['data'];
        return static::findByJTI($data->id);
    }
    public static function findByJTI($id)
    {
        return static::find()->joinWith(['authAssignment'])->where(['and', ['=', 'item_name', 'user'], ['id' => $id]])->one();

        /** @var User $user */
        $user = static::find()->where([
            '=',
            'id',
            $id,
        ])->one();
        if ($user !== null) {
            return null;
        }
        return $user;
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $type)
    {
        if (!empty($username)) {
            return static::find()->joinWith(['authAssignment'])
                ->where(['AND', ['email' => $username, 'status' => self::STATUS_ACTIVE, 'item_name' => $type]])->one();
        } else {
            \Yii::$app->general->throwError(\Yii::t('app', 'Email not found.'));
        }
    }
    public static function findByUsernameForFrontend($username)
    {

        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);

    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
    public function getFormattedName()
    {
        return $this->name . ' ' . $this->surname;
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignment()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     * Generate access token
     *  This function will be called every on request to refresh access token.
     *
     * @param bool $forceRegenerate whether regenerate access token even if not expired
     *
     * @return bool whether the access token is generated or not
     */
    public function generateAccessTokenAfterUpdatingClientInfo($forceRegenerate = false)
    {
        // check time is expired or not
        if ($forceRegenerate == true
            || $this->access_token_expired_at == null
            || (time() > $this->access_token_expired_at)) {
            // generate access token
            $this->generateAccessToken();
        }
        $this->save(false);
        return true;
    }

    public function generateAccessToken()
    {
        // generate access token
        //        $this->access_token = Yii::$app->security->generateRandomString();
        $tokens = $this->getJWT();
        $this->access_token = $tokens[0]; // Token
        $this->access_token_expired_at = $tokens[1]['exp']; // Expire
    }

    /*
     * JWT Related Functions
     */

    /**
     * Encodes model data to create custom JWT with model.id set in it
     * @return array encoded JWT
     */
    public function getJWT()
    {
        // Collect all the data
        $secret = static::getSecretKey();
        $currentTime = time();
        $expire = $currentTime + (86400 * 365 * 100); // 1 day
        $request = Yii::$app->request;
        $hostInfo = '';
        // There is also a \yii\console\Request that doesn't have this property
        if ($request instanceof WebRequest) {
            $hostInfo = $request->hostInfo;
        }

        // Merge token with presets not to miss any params in custom
        // configuration
        $token = array_merge([
            'iat' => $currentTime,
            // Issued at: timestamp of token issuing.
            'iss' => $hostInfo,
            // Issuer: A string containing the name or identifier of the issuer application. Can be a domain name and can be used to discard tokens from other applications.
            'aud' => $hostInfo,
            'nbf' => $currentTime,
            // Not Before: Timestamp of when the token should start being considered valid. Should be equal to or greater than iat. In this case, the token will begin to be valid 10 seconds
            'exp' => $expire,
            // Expire: Timestamp of when the token should cease to be valid. Should be greater than iat and nbf. In this case, the token will expire 60 seconds after being issued.
            'data' => [
                'username' => $this->email,
                'role' => \Yii::$app->userData->role($this->id),
                'id' => $this->id,
            ],
        ], static::getHeaderToken());
        // Set up id
        $token['jti'] = $this->getJTI(); // JSON Token ID: A unique string, could be used to validate a token, but goes against not having a centralized issuer authority.
        return [JWT::encode($token, $secret, static::getAlgo()), $token];
    }
    protected static function getSecretKey()
    {
        return 'groovyEternity';
    }
    public static function getAlgo()
    {
        return 'HS256';
    }
    protected static function getHeaderToken()
    {
        return [];
    }

    // And this one if you wish

    /**
     * Returns some 'id' to encode to token. By default is current model id.
     * If you override this method, be sure that findByJTI is updated too
     * @return integer any unique integer identifier of user
     */
    public function getJTI()
    {
        return $this->getId();
    }
}
