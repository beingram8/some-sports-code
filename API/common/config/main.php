<?php
$params = array_merge(
    require __DIR__ . '/params.php'
);
$config = [
    'timezone' => 'Europe/Rome',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['Cron-FinishMatch'],
                    'exportInterval' => 1,
                    'logFile' => '@frontend/runtime/logs/Cron-FinishMatch.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['Notification'],
                    'exportInterval' => 1,
                    'logFile' => '@frontend/runtime/logs/notification.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['Cron-EnableVote', 'WinnerIsNotSaved', 'FixtureDataMissing', 'MatchDataNotSaved', 'PlayerDataNotSet', 'FailedToUpdateMatch'],
                    'exportInterval' => 1,
                    'logFile' => '@frontend/runtime/logs/Cron.log',
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error'],
                ],
                // [
                //     'class' => 'yii\log\EmailTarget',
                //     'levels' => ['error', 'warning'],
                //     'mailer' => 'mailer',
                //     'categories' => ['yii\db\*'],
                //     'message' => [
                //         'from' => ['log@example.com'],
                //         'to' => ['sagarpatel8153@gmail.com'],
                //         'subject' => 'Database errors at eternity',
                //     ],
                // ],

            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ],
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ],
            ],
        ],
        'formatter' => [
            'thousandSeparator' => ',',
            'currencyCode' => 'EUR',
            'dateFormat' => 'php:d-m-Y',
            'datetimeFormat' => 'php:d-m-Y H:i',
            'timeFormat' => 'php:H:i',
        ],
        'emailtemplate' => [
            'class' => 'vendor\groovy\src\email\components\EmailsTemplate',
            'allowDelete' => false,
            'allowInsert' => true,
            'dummycontent' => dirname(dirname(__DIR__)) . "/frontend/web/emailtemplate/dummy.html",
            'icons' => ["update" => "glyphicon glyphicon-pencil", "view" => "glyphicon glyphicon-eye-open", "delete" => "glyphicon glyphicon-trash"],
            'breadcrumbs' => dirname(dirname(__DIR__)) . "/frontend/web/breadcrumbs/breadcrumbs.php",
        ],
        'storage' => [
            'class' => 'bilberrry\spaces\Service',
            'credentials' => [
                'key' => !empty($params['digital_o_key']) ? $params['digital_o_key'] : "",
                'secret' => !empty($params['digital_o_secret']) ? $params['digital_o_secret'] : "",
            ],
            'region' => 'fra1', // currently available: nyc3, ams3, sgp1, sfo2
            'defaultSpace' => 'fanrating',
            'defaultAcl' => 'public-read',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'notification' => [
            'class' => 'common\components\Notification',
        ],
        'cron' => [
            'class' => 'common\components\Cron',
        ],
        'lang' => [
            'class' => 'common\components\Language',
        ],
        'quiz' => [
            'class' => 'common\components\Quiz',
        ],
        'survey' => [
            'class' => 'common\components\Survey',
        ],
        'reward' => [
            'class' => 'common\components\Reward',
        ],
        'time' => [
            'class' => 'common\components\Time',
        ],
        'general' => [
            'class' => 'common\components\General',
        ],
        'player' => [
            'class' => 'common\components\Player',
        ],
        'season' => [
            'class' => 'common\components\Season',
        ],
        'league' => [
            'class' => 'common\components\League',
        ],
        'match' => [
            'class' => 'common\components\Match',
        ],
        'team' => [
            'class' => 'common\components\Team',
        ],
        'userData' => [
            'class' => 'common\components\UserData',
        ],
        'system' => [
            'class' => 'common\components\System',
        ],
        'token' => [
            'class' => 'common\components\Token',
        ],
        'dashboard' => [
            'class' => 'common\components\Dashboard',
        ],
        'fetch' => [
            'class' => 'common\components\Fetch',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'img' => [
            'class' => 'common\components\Img',
        ],
        'push' => [
            'class' => 'common\components\Push',
        ],
        'stripe' => [
            'class' => 'common\components\StripePay',
        ],
        'assetManager' => [
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false, // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],

    ],
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            // ...
            'migrations' => [
                'class' => \ymaker\gii\migration\Generator::class,
            ],
        ],
    ];
}
return $config;
