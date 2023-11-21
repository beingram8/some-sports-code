<?php
namespace backend\modules\v1\controllers;

use backend\filters\auth\HttpBearerAuth;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class MasterSyncController extends ActiveController
{
    public $modelClass = '';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],

        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'interface' => ['post'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'test',
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['users', 'create-user', 'block-contract', 'sync-allowed-module', 'sync-interface', 'sync-system-params', 'sync-credit-buy-payment-method', 'test-db-connection', 'test-smtp-connection', 'change-smtp', 'user-geolocation', 'user-devices', 'avg-attention-time', 'change-db'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['users', 'block-contract', 'sync-allowed-module', 'create-user', 'sync-interface', 'sync-system-params', 'sync-credit-buy-payment-method', 'test-db-connection', 'test-smtp-connection', 'change-smtp', 'user-geolocation', 'user-devices', 'avg-attention-time', 'change-db'],
                    'roles' => ['@'],
                    // 'ips' => \Yii::$app->params['master_ips'],
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionTest()
    {
        return ['status' => false, 'message' => 'Parameters are missing..'];
    }
    public function actionCreateUser()
    {
        $model = new \common\models\Employee(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save() && $model->roleAssignment($model->id, $model->role)) {
                $user = \common\models\Employee::findOne($model->id);
                $user->setPassword($model->password_hash);
                $user->generateAuthKey();
                if ($user->save()) {
                    return ['status' => true];
                } else {
                    $errors = $user->errors;
                    $user->delete();
                    return ['status' => false, 'message' => json_encode($errors)];
                }
            } else {
                return ['status' => false, 'message' => json_encode($model->errors)];
            }
        } else {
            return ['status' => false, 'message' => 'Parameters are missing..'];
        }
    }
    public function actionUserGeolocation()
    {
        return \common\models\Employee::find()->select(['system_address_lat', 'system_address_lng'])->where(['!=', 'role', 'sync_user'])->asArray()->all();
    }
    public function actionUserDevices()
    {
        $data['browser'] = \common\models\Employee::find()
            ->select(['system_browser', 'COUNT(id) as total'])->where(['!=', 'role', 'sync_user'])->groupBy(['system_browser'])->asArray()->all();
        $data['os'] = \common\models\Employee::find()
            ->select(['system_os', 'COUNT(id) as total'])->where(['!=', 'role', 'sync_user'])->groupBy(['system_os'])->asArray()->all();
        $data['server'] = $_SERVER;
        return $data;
    }
    public function actionUsers()
    {
        return \common\models\Employee::find()->where(['!=', 'role', 'sync_user'])->asArray()->all();
    }
    public function actionAvgAttentionTime()
    {
        $data = \common\models\Appointment::find()
            ->select(['department.name', '(AVG(appointment.close_datetime - appointment.start_datetime)/60) as avg_time'])
            ->join('INNER JOIN', 'department', 'appointment.department_id = department.id')
            ->where(['IN', 'appointment.status', ['Attended']])
            ->groupBy(['appointment.department_id'])
            ->asArray()->all();
        return $data;
    }
    public function actionSyncInterface()
    {
        $model = new \common\models\InterfaceConfiguration;
        $model->load(Yii::$app->request->post());
        if ($model->primary_bg_color) {
            $model = \common\models\InterfaceConfiguration::find()->where(['primary_bg_color' => $model->primary_bg_color])->one();
            $model = !empty($model) ? $model : new \common\models\InterfaceConfiguration;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return ['status' => true];
            } else {
                return ['status' => false, 'message' => $model->errors];
            }
        } else {
            return ['status' => false, 'message' => 'InterfaceConfiguration can not be blank'];
        }
    }
    public function actionSyncAllowedModule()
    {
        if (isset($_POST['module_codes'])) {
            \Yii::$app->db->createCommand('TRUNCATE TABLE `allowed_module`')->execute();
            if (!empty($_POST['module_codes'])) {
                foreach ($_POST['module_codes'] as $module_code) {
                    $model = new \common\models\AllowedModule;
                    $model->module_code = $module_code;
                    $model->is_active = 1;
                    $model->updated_at = time();
                    if ($model->save(false)) {
                    }
                }
            }
            return ['status' => true];
        } else {
            return ['status' => false, 'message' => 'Key does not exist'];
        }
    }
    public function actionBlockContract()
    {
        $model = \common\models\MsSystemparameters::find()->where(['name' => 'contract_enabled'])->one();
        if ($model) {
            if (isset($_POST['contract_enabled'])) {
                $model->value = $_POST['contract_enabled'];
                if ($model->save()) {
                    return ['status' => true, 'message' => 'Contract updated.'];
                } else {
                    return ['status' => true, 'message' => json_encode($model->errors)];
                }
            } else {
                return ['status' => false, 'message' => '"contract_enabled" is not exist.'];
            }
        } else {
            return ['status' => false, 'message' => '"contract_enabled" is not setup yet.'];
        }
    }
    public function actionSyncSystemParams()
    {
        $model = new \common\models\MsSystemparameters;
        $model->load(Yii::$app->request->post());
        if ($model->name) {
            $model = \common\models\MsSystemparameters::find()->where(['name' => $model->name])->one();
            $model = !empty($model) ? $model : new \common\models\MsSystemparameters;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return ['status' => true];
            } else {
                return ['status' => false, 'message' => $model->errors];
            }
        } else {
            return ['status' => false, 'message' => 'Name can not be blank'];
        }
    }
    public function actionSyncCreditBuyPaymentMethod()
    {
        $model = new \common\models\MsCreditBuyPaymentMethod;
        $model->load(Yii::$app->request->post());
        if ($model->method_name) {
            $model = \common\models\MsCreditBuyPaymentMethod::find()
                ->where(['method_name' => $model->method_name])->one();

            $model = !empty($model) ? $model : new \common\models\MsCreditBuyPaymentMethod;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return ['status' => true];
            } else {
                return ['status' => false, 'message' => $model->errors];
            }
        } else {
            return ['status' => false, 'message' => 'Method can not be blank'];
        }
    }
    private function checkSMTPConnection($smtp_host, $smtp_port, $smtp_user, $smtp_pwd, $smtp_test_email)
    {
        try {
            $transport = (new Swift_SmtpTransport($smtp_host, $smtp_port))
                ->setUsername($smtp_user)
                ->setPassword($smtp_pwd);
            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message('SMTP Test'))
                ->setFrom(array($smtp_test_email => 'Eternity Test SMTP Connection'))
                ->setTo(array($smtp_test_email))
                ->setBody('$body = "Test SMTP Connection"\n";');

            $result = $mailer->send($message);
            return ['status' => true, 'message' => 'SMTP Working..'];
        } catch (Swift_TransportException $e) {
            return ['status' => true, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => true, 'message' => $e->getMessage()];
        }
    }
    public function actionTestSmtpConnection()
    {
        if (!empty($_POST['smtp_host']) && !empty($_POST['smtp_port']) && !empty($_POST['smtp_user']) &&
            !empty($_POST['smtp_pwd']) && !empty($_POST['smtp_enc']) && !empty($_POST['smtp_testing_email'])) {
            $smtp_host = $_POST['smtp_host'];
            $smtp_port = $_POST['smtp_port'];
            $smtp_user = $_POST['smtp_user'];
            $smtp_pwd = $_POST['smtp_pwd'];
            $smtp_enc = $_POST['smtp_enc'];
            $smtp_testing_email = $_POST['smtp_testing_email'];
            return $this->checkSMTPConnection($smtp_host, $smtp_port, $smtp_user, $smtp_pwd, $smtp_testing_email);
        } else {
            return ['status' => false, 'message' => 'Parameters are missing'];
        }
    }
    public function actionChangeSmtp()
    {
        if (!empty($_POST['smtp_host']) && !empty($_POST['smtp_user']) && !empty($_POST['smtp_pwd']) && !empty($_POST['smtp_port'])
            && !empty($_POST['smtp_enc']) && !empty($_POST['smtp_testing_email'])) {
            $smtp_file = \Yii::$app->basePath . '/../common/config/mail.php';
            if (file_exists($smtp_file)) {
                $smtp_host = $_POST['smtp_host'];
                $smtp_port = $_POST['smtp_port'];
                $smtp_user = $_POST['smtp_user'];
                $smtp_pwd = $_POST['smtp_pwd'];
                $smtp_enc = $_POST['smtp_enc'];
                $smtp_testing_email = $_POST['smtp_testing_email'];
                try {
                    $res = $this->checkSMTPConnection($smtp_host, $smtp_port, $smtp_user, $smtp_pwd, $smtp_testing_email);
                    if (!empty($res['status']) && $res['status'] == true) {
                        $content = "<?php
                        return [
                            'class' => 'yii\swiftmailer\Mailer',
                            'viewPath' => '@common/mail',
                            // send all mails to a file by default. You have to set
                            // 'useFileTransport' to false and configure a transport
                            // for the mailer to send real emails.
                            'useFileTransport' => false,
                            'transport' => [
                                'class' => 'Swift_SmtpTransport',
                                'host' => '" . $smtp_host . "', // e.g. smtp.mandrillapp.com or smtp.gmail.com
                                'username' => '" . $smtp_user . "',
                                'password' => '" . $smtp_pwd . "',
                                'port' => '" . $smtp_port . "', // Port 25 is a very common port too
                                'encryption' => '" . $smtp_enc . "' // It is often used, check your provider or mail server specs
                            ],
                        ];";
                        $fp = fopen($smtp_file, 'w+');
                        fwrite($fp, $content);
                        fclose($fp);
                        return ['status' => true, 'message' => 'Smtp connected..'];
                    } else {
                        return $res;
                    }

                } catch (\yii\base\ErrorException $e) {
                    return ['status' => false, 'message' => $e->getMessage()];
                }
            } else {
                return ['status' => false, 'message' => 'Smtp mail file is not exist.'];
            }
        } else {
            return ['status' => false, 'message' => 'Parameters are missing'];
        }
    }
    private function checkDBConnection($db_host, $db_username, $db_password, $db_name)
    {
        try {
            $conn = new \mysqli($db_host, $db_username, $db_password, $db_name);
            // Check connection
            if ($conn->connect_error) {
                return ['status' => false, 'message' => $conn->connect_error];
            }
            return ['status' => true, 'message' => 'DB connected..'];
        } catch (\yii\base\ErrorException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    public function actionTestDbConnection()
    {
        if (!empty($_POST['host']) && !empty($_POST['db']) && !empty($_POST['username']) && isset($_POST['password'])) {
            $db_host = $_POST['host'];
            $db_name = $_POST['db'];
            $db_username = $_POST['username'];
            $db_password = $_POST['password'];
            return $this->checkDBConnection($db_host, $db_username, $db_password, $db_name);
        } else {
            return ['status' => false, 'message' => 'Parameters are missing'];
        }
    }
    public function actionChangeDb()
    {
        if (!empty($_POST['host']) && !empty($_POST['db']) && !empty($_POST['username']) && isset($_POST['password'])) {
            $db_file = \Yii::$app->basePath . '/../common/config/db.php';
            if (file_exists($db_file)) {
                $db_host = $_POST['host'];
                $db_name = $_POST['db'];
                $db_username = $_POST['username'];
                $db_password = $_POST['password'];

                $res = $this->checkDBConnection($db_host, $db_username, $db_password, $db_name);
                if (!empty($res['status']) && $res['status'] == true) {
                    $content = "<?php
                    return [
                        'class' => 'yii\db\Connection',
                        'dsn' => 'mysql:host=" . $db_host . ";dbname=" . $db_name . "',
                        'username' => '" . $db_username . "',
                        'password' => '" . $db_password . "',
                        'charset' => 'utf8',
                    ];";
                    $fp = fopen($db_file, 'w+');
                    fwrite($fp, $content);
                    fclose($fp);
                    return ['status' => true, 'message' => 'DB is connected'];
                } else {
                    return $res;
                }
            } else {
                return ['status' => false, 'message' => 'DB file is not exist.'];
            }
        } else {
            return ['status' => false, 'message' => 'Parameters are missing'];
        }
    }
    public function actionOptions($id = null)
    {
        return 'ok';
    }
}