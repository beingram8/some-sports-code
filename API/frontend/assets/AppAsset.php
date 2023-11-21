<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font.css?family=Poppins:300,400,500,600,700',
        'css/login.css?v=7.2.6',
        'css/custom.css',
        'css/plugins.css?v=7.2.6',
        'css/style.css?v=7.2.6',
        'css/kanban.css',
        'css/wizard.css',
        'css/countdown.css',
        'css/daterangepicker.css',
    ];
    public $js = [
        [
            'js/plugins.js?v=7.2.6', 'position' => \yii\web\View::POS_HEAD,
        ],
        'js/moment.min.js',
        'js/moment-with-locales.min.js',
        'js/daterangepicker.min.js',
        'js/countdown.js',
        'js/custom.js',
        'js/scripts.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}