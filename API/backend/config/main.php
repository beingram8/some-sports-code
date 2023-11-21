<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);
return [
    'id' => 'app-backend',
    'language' => 'en-US',
    'name' => 'Fan Rating',
    'timezone' => 'Europe/Rome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'translatemanager'],
    'modules' => [
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'root' => [
                '@frontend',
                '@backend',
                '@common',
            ], // The root directory of the project scan.
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

        'backuprestore' => [
            'class' => '\oe\modules\backuprestore\Module',
        ],
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
        'gridview' => ['class' => 'kartik\grid\Module'],
        'email' => [
            'class' => 'vendor\groovy\src\email\Module',
        ],
        'v1' => [
            'class' => 'backend\modules\v1\Module',
        ],
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@yii2mod/rbac/views' => '@app/views/rbac',
                    '@oe/modules/backuprestore/views/default' => '@app/views/dbbackup',
                    '@vendor/groovy/src/email/views/default' => '@app/views/emailtemplate',
                    '@vendor/agsachdev/groovy-trans/views' => '@app/views/lajax',
                ],
            ],
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'class' => 'common\components\Request',
            'web' => '/backend/web',
            'adminUrl' => '',
            'cookieValidationKey' => 'cMr2rLVvljIhN-CjvZi206hyFW7xZTdD',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                'forget-password' => 'site/forget-password',
                'language/<lang_id>' => 'site/change-lang',
                'verified/<token>' => 'site/verify-email',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'authTimeout' => 3600,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    // 'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
                    'js' => [],

                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
            'appendTimestamp' => true,
        ],
    ],
    'params' => $params,
];