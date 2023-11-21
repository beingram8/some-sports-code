<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);
$l = "en-US";
if (!empty($_GET['lang_id'])) {
    $l = $_GET['lang_id'];
} else if (!empty($_COOKIE['lang_id'])) {
    $l = $_COOKIE['lang_id'];
}
return [
    'id' => 'app-frontend',
    'language' => 'it-IT',
    'name' => 'Fan Rating',
    'sourceLanguage' => 'en-US',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'translatemanager'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'root' => '@frontend', // The root directory of the project scan.
            'scanRootParentDirectory' => true, // Whether scan the defined `root` parent directory, or the folder itself.
            // IMPORTANT: for detailed instructions read the chapter about root configuration.
            'layout' => null, // Name of the used layout. If using own layout use 'null'.
            'allowedIPs' => ['*'], // IP addresses from which the translation interface is accessible.
            //'roles' => ['@'],               // For setting access levels to the translating interface.
            'tmpDir' => '@runtime', // Writable directory for the client-side temporary language files.
            // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
            'phpTranslators' => ['::t'], // list of the php function for translating messages.
            'jsTranslators' => ['lajax.t'], // list of the js function for translating messages.
            'patterns' => ['*.js', '*.php'], // list of file extensions that contain language elements.
            'ignoredCategories' => ['yii'], // these categories won't be included in the language database.
            'ignoredItems' => ['config'], // these files will not be processed.
            'scanTimeLimit' => null, // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
            'searchEmptyCommand' => '!', // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
            'defaultExportStatus' => 1, // the default selection of languages to export, set to 0 to select all languages by default
            'defaultExportFormat' => 'json', // the default format for export, can be 'json' or 'xml'
            'tables' => [ // Properties of individual tables
                [
                    'connection' => 'db', // connection identifier
                    'table' => '{{%language}}', // table name
                    'columns' => ['name', 'name_ascii'], // names of multilingual fields
                    'category' => 'database-table-name', // the category is the database table name
                ],
            ],
            'scanners' => [ // define this if you need to override default scanners (below)
                '\lajax\translatemanager\services\scanners\ScannerPhpFunction',
                '\lajax\translatemanager\services\scanners\ScannerPhpArray',
                '\lajax\translatemanager\services\scanners\ScannerJavaScriptFunction',
                '\lajax\translatemanager\services\scanners\ScannerDatabase',
            ],
        ],
        'v1' => [
            'class' => 'frontend\modules\v1\Module',
        ],
    ],
    'components' => [
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Component',
        ],
        'redsys' => [
            'class' => 'common\components\Redsys',
        ],
        'request' => [
            'class' => 'common\components\Request',
            'web' => '/frontend/web',
            'cookieValidationKey' => 'cMr2rLVvljIhN-CjvZi206hyFW7xZTdD',
            // 'parsers' => [
            //     'application/json' => 'yii\web\JsonParser',
            // ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->format == 'html' || $response->format == 'raw') {
                    return $response;
                }
                $responseData = $response->data;
                if ($response->statusCode >= 200 && $response->statusCode <= 299) {
                    $response->data = [
                        'status' => $responseData['status'],
                        'data' => !empty($responseData['data']) ? $responseData['data'] : "",
                        'message' => !empty($responseData['message']) ? $responseData['message'] : "",
                        'http_status_code' => $response->statusCode,
                    ];
                } else {
                    $response->data = [
                        'status' => false,
                        'message' => !empty($responseData['message']) ? $responseData['message'] : "",
                        'http_status_code' => $response->statusCode,
                    ];
                }
                return $response;
            },
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'request-password-reset' => 'site/request-password-reset',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'start' => 'site/index',
                'how-it-work' => 'site/how-it-works',
                'legal' => 'site/legal',
                'verified/<token>' => '/site/verify-email',
                'appointment/position/<department_id>' => '/appointment/position',
                'appointment/appointment-type/<department_id>/<position_id>' => '/appointment/appointment-type',

            ],

        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],
    'params' => $params,
];
