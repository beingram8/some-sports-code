<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css',
        'css/font.css?family=Poppins:300,400,500,600,700',
        'css/login.css?v=7.2.6',
        'css/custom.css',
        'css/plugins.css?v=7.2.6',
        'css/style.css?v=7.2.6',
        'css/kanban.css',
        'css/countdown.css',
        'css/animate.min.css',
    ];
    public $js = [
        [
            'js/plugins.js?v=7.2.6', 'position' => \yii\web\View::POS_HEAD,
        ],
        'https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js',
        'js/countdown.js',
        'js/alert.js',
        'js/custom.js',
        'js/scripts.js',
        'js/sweetalert.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}