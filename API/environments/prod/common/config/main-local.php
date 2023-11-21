<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=fr_pwa_live',
            'username' => 'root',
            'password' => '10.dU{}ABY@fanol',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.eu.mailgun.org', // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'postmaster@mg.fanratingweb.com',
                'password' => 'cb40f24fa057e960a53de930cbffca15-9ad3eb61-f2ac3105',
                'port' => '587', // Port 25 is a very common port too
                'encryption' => 'tls', // It is often used, check your provider or mail server specs
            ],
        ],
    ],
];