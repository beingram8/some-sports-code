<?php

use backend\assets\AppAsset;
use lajax\translatemanager\helpers\Language as Lx;
use yii\helpers\Html;
use yii\helpers\Url;
$login_user = Yii::$app->user->identity;
\lajax\translatemanager\helpers\Language::registerAssets();
AppAsset::register($this);
$module = Yii::$app->controller->module->id;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$module = Yii::$app->controller->module->id;
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">

<head>
    <meta charset="<?=Yii::$app->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <link rel="icon" type="image/x-icon" href="<?=Url::base() . '/../img_assets/fan-rating.png'?>">
    <?php $this->head()?>
</head>
<?php include_once 'url.php';?>

<body>
    <?php $this->beginBody()?>
    <div id="preloader" class="loader hide"></div>

    <body id="kt_body" style="background-image: url(<?php echo Url::to('@web/') ?>media/bg/new.png)"
        class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled page-loading">
        <!-- End Google Tag Manager (noscript) -->
        <!--begin::Main-->
        <!--begin::Header Mobile-->
        <div id="kt_header_mobile" class="header-mobile">
            <!--begin::Logo-->
            <a href="<?php echo Url::to(['/site/index']); ?>">
                <img alt="Logo" src="<?php echo Url::to('@web/') ?>/media/logos/site-logo.png"
                    class="logo-default max-h-30px" />
            </a>
            <!--end::Logo-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
                    <span></span>
                </button>
                <button class="btn btn-icon btn-hover-transparent-white p-0 ml-3" id="kt_header_mobile_topbar_toggle">
                    <span class="svg-icon svg-icon-xl">
                        <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/General/User.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path
                                    d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path
                                    d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                    fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                </button>
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Header Mobile-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Page-->
            <div class="d-flex flex-row flex-column-fluid page">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                    <!--begin::Header-->
                    <div id="kt_header" class="header header-fixed">
                        <!--begin::Container-->
                        <div class="container d-flex align-items-stretch justify-content-between">
                            <!--begin::Left-->
                            <div class="d-flex align-items-stretch mr-3">
                                <!--begin::Header Logo-->
                                <div class="header-logo">
                                    <a href="<?php echo Url::to(['/site/index']); ?>">
                                        <img alt="Logo" src="<?=Url::base() . '/../img_assets/fan-rating.png'?>"
                                            class="logo-default max-h-40px" />
                                        <img alt="Logo" src="<?=Url::base() . '/../img_assets/fan-rating.png'?>"
                                            class="logo-sticky max-h-40px" />
                                    </a>
                                </div>
                                <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                                    <div id="kt_header_menu"
                                        class="header-menu header-menu-left header-menu-mobile header-menu-layout-default">
                                        <!--begin::Header Nav-->
                                        <ul class="menu-nav">
                                            <li class="menu-item ">
                                                <a href="<?php echo Url::to(['/account/dashboard']); ?>"
                                                    class="menu-link <?=$controller == "account" && $action == "dashboard" ? "menu-active" : "";?>">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fa fa-home"></i>
                                                    </span>
                                                    <span class="menu-text">
                                                        <?=Lx::t('app', 'Dashboard');?></span>
                                                    <i class="menu-arrow"></i>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu menu-item-rel"
                                                data-menu-toggle="click" aria-haspopup="true">
                                                <a href="javascript:;"
                                                    class="menu-link menu-toggle <?=$controller == "user" ? "menu-active" : "";?> ">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fa fa-users"></i>
                                                    </span>
                                                    <span class="menu-text"><?=Yii::t('app', 'Users');?></span>
                                                    <span class="menu-desc"></span>
                                                    <i class="menu-arrow"></i>
                                                </a>
                                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                                    <ul class="menu-subnav">
                                                        <li
                                                            class="menu-item <?=$controller == "user" && $action == "index" || $action == "view" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/user/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fa fa-users"></i>
                                                                </span>
                                                                <span
                                                                    class="menu-text"><?=Yii::t('app', 'App Users')?></span>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item menu-item-submenu menu-item-rel"
                                                data-menu-toggle="click" aria-haspopup="true">
                                                <a href="javascript:;" class="menu-link menu-toggle
                                                <?=$controller == "match" || $controller == "season" || $controller == "season-league"
|| $controller == "season-team" || $controller == "player" || $controller == 'match-vote' || $controller == 'winner' ? "menu-active" : "";?>
                                                ">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fa fa-database"></i>
                                                    </span>
                                                    <span class="menu-text">Matches</span>
                                                    <span class="menu-desc"></span>
                                                    <i class="menu-arrow"></i>
                                                </a>
                                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                                    <ul class="menu-subnav">
                                                        <li
                                                            class="menu-item <?=$controller == "season" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/season/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-futbol"></i>
                                                                </span>
                                                                <span class="menu-text">Season</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "season-league" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/season-league/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-futbol"></i>
                                                                </span>
                                                                <span class="menu-text">Leagues</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "season-team" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/season-team/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-user-friends"></i>
                                                                </span>
                                                                <span class="menu-text">Teams</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "match" ? "menu-item-active" : "";?> ">
                                                            <a href="<?php echo Url::to(['/match/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fab fa-fantasy-flight-games"></i>
                                                                </span>
                                                                <span class="menu-text">Matches</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "player" ? "menu-item-active" : "";?> ">
                                                            <a href="<?php echo Url::to(['/player/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fab fa-fantasy-flight-games"></i>
                                                                </span>
                                                                <span class="menu-text">Players</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item menu-item-submenu menu-item-rel"
                                                data-menu-toggle="click" aria-haspopup="true">
                                                <a href="javascript:;"
                                                    class="menu-link menu-toggle
                                                <?=$controller == "quiz" || $controller == "news" || $controller == "streaming" || $controller == "video" || $controller == "survey" || $controller == "cms" ? "menu-active" : "";?>">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fas fa-cogs"></i>
                                                    </span>
                                                    <span class="menu-text">CMS</span>
                                                    <span class="menu-desc"></span>
                                                    <i class="menu-arrow"></i>
                                                </a>
                                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                                    <ul class="menu-subnav">
                                                        <li
                                                            class="menu-item <?=$controller == "news" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/news/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-newspaper"></i>
                                                                </span>
                                                                <span class="menu-text">News</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "video" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/video/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-video"></i>
                                                                </span>
                                                                <span class="menu-text">Video</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "quiz" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/quiz/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="far fa-question-circle"></i>
                                                                </span>
                                                                <span class="menu-text">Quiz</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "survey" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/survey/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-question-circle"></i>
                                                                </span>
                                                                <span class="menu-text">Survey</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "cms" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/cms/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="far fa-file-alt"></i>
                                                                </span>
                                                                <span class="menu-text">CMS pages</span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "streaming" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/streaming/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-camera-retro"></i>
                                                                </span>
                                                                <span class="menu-text">Streaming</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item menu-item-submenu menu-item-rel"
                                                data-menu-toggle="click" aria-haspopup="true">
                                                <a href="javascript:;"
                                                    class="menu-link menu-toggle <?=$controller == "reward-category" || $controller == "reward-product" ? "menu-active" : "";?>">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fas fa-award"></i>
                                                    </span>
                                                    <span class="menu-text"><?=Yii::t('app', 'Reward');?></span>
                                                    <span class="menu-desc"></span>
                                                    <i class="menu-arrow"></i>
                                                </a>
                                                <div class="menu-submenu menu-submenu-classic menu-submenu-left"
                                                    data-hor-direction="menu-submenu-left">
                                                    <ul class="menu-subnav">
                                                        <li
                                                            class="menu-item <?=$controller == "reward-category" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/reward-category/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fa fa-list-alt"></i>
                                                                </span>
                                                                <span
                                                                    class="menu-text"><?=Yii::t('app', 'Category');?></span>
                                                                <span class="menu-desc"></span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "reward-product" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/reward-product/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fa fa-list-alt"></i>
                                                                </span>
                                                                <span
                                                                    class="menu-text"><?=Yii::t('app', 'Product');?></span>
                                                                <span class="menu-desc"></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item menu-item-submenu menu-item-rel"
                                                data-menu-toggle="click" aria-haspopup="true">
                                                <a href="javascript:;"
                                                    class="menu-link menu-toggle <?=$controller == "teasing-room" ? "menu-active" : "";?>">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fab fa-rocketchat"></i>
                                                    </span>
                                                    <span class="menu-text"><?=Yii::t('app', 'Teasing Room');?></span>
                                                    <span class="menu-desc"></span>
                                                    <i class="menu-arrow"></i>
                                                </a>
                                                <div class="menu-submenu menu-submenu-classic menu-submenu-left"
                                                    data-hor-direction="menu-submenu-left">
                                                    <ul class="menu-subnav">
                                                        <li
                                                            class="menu-item <?=$controller == "teasing-room" && $action == "index" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['teasing-room/index']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-list-ul"></i>
                                                                </span>
                                                                <span
                                                                    class="menu-text"><?=Yii::t('app', 'Teasing Room List');?></span>
                                                                <span class="menu-desc"></span>
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="menu-item <?=$controller == "teasing-room" && $action == "report-list" ? "menu-item-active" : "";?>">
                                                            <a href="<?php echo Url::to(['/teasing-room/report-list']); ?>"
                                                                class="menu-link">
                                                                <span class="svg-icon menu-icon">
                                                                    <i class="fas fa-clipboard-list"></i>
                                                                </span>
                                                                <span
                                                                    class="menu-text"><?=Yii::t('app', 'Reported List');?></span>
                                                                <span class="menu-desc"></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php $pending_contacts = \common\models\ContactUs::find()->where(['status' => 0])->count();?>
                                            <li
                                                class="menu-item <?=$controller == "contact-us" ? "menu-item-active" : "";?>">
                                                <a href="<?php echo Url::to(['/contact-us/index']); ?>"
                                                    class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                        <i class="fas fa-users"></i>
                                                    </span>
                                                    <span class="menu-text">Contact-Us List</span>
                                                    <?php if ($pending_contacts > 0) {?>
                                                    <span
                                                        class="label label-danger label-inline ml-3"><?=$pending_contacts?></span>
                                                    <?php }?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="topbar">
                                <!--begin::Quick Actions-->
                                <div class="dropdown">
                                    <!--begin::Toggle-->
                                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                                        <div
                                            class="btn <?=$module == "email" || $controller == "system" || $controller == "notification" || $controller == "user-city" || $controller == "user-education" || $controller == "user-job" || $controller == "token-type" || $controller == "token-plan" || $controller == "user-level" ? "menu-active" : "";?> btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1">
                                            <span class="svg-icon svg-icon-xl">
                                                <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Media/Equalizer.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path
                                                            d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z"
                                                            fill="#000000"></path>
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Toggle-->
                                    <!--begin::Dropdown-->
                                    <div
                                        class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                                        <!--begin:Header-->
                                        <div class="d-flex flex-column flex-center py-10 bgi-size-cover bgi-no-repeat rounded-top"
                                            style="background:#000000">
                                            <h4 class="text-white font-weight-bold">
                                                <?php echo Yii::t('app', 'System Configuration'); ?></h4>
                                        </div>
                                        <!--end:Header-->
                                        <!--begin:Nav-->
                                        <div class="row row-paddingless">
                                            <!--begin:Item-->
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/notification/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-bell text-primary fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'Notification'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/email/default/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-success">
                                                        <i class="fa fa-envelope text-warning fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'Email Templates'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/token-type/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-key fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'Token type Management'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/token-plan/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-key fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'Token Plan Management'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/user-level/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-user fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'User Level Management'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/user-city/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fas fa-city text-primary fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'User City list'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/user-education/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-graduation-cap text-primary fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'User Education list'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/user-job/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-tasks text-primary fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'User Job list'); ?></span>
                                                </a>
                                            </div>

                                            <!--end:Item-->
                                        </div>
                                        <!--end:Nav-->
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <div class="dropdown">
                                    <!--begin::Toggle-->
                                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                                        <div
                                            class="btn <?=$controller == "cron" || $module == "translatemanager" || $controller == "log" || $controller == "module" || $controller == "match-fetch" ? "menu-active" : "";?> btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1">
                                            <span class="svg-icon svg-icon-xl">
                                                <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Code/Compiling.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path
                                                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                                            fill="#000000" opacity="0.3"></path>
                                                        <path
                                                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                                            fill="#000000"></path>
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Toggle-->
                                    <!--begin::Dropdown-->
                                    <div
                                        class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                                        <!--begin:Header-->
                                        <div class="d-flex flex-column flex-center py-10 bgi-size-cover bgi-no-repeat rounded-top"
                                            style="background:#000000">
                                            <h4 class="text-white font-weight-bold">
                                                <?php echo Yii::t('app', 'Developer Panel'); ?></h4>
                                        </div>
                                        <!--end:Header-->
                                        <!--begin:Nav-->
                                        <div class="row row-paddingless">
                                            <!--begin:Item-->
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/cron/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-success">
                                                        <i class="fa fa-clock text-warning fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'System Cron Log'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/log/index']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-bug text-danger  fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'System Error Log'); ?></span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a href="<?=Url::toRoute(['/translatemanager']);?>"
                                                    class="d-block py-10 px-5 text-center bg-hover-light border-right border-bottom">
                                                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                        <i class="fa fa-language text-primary fa-2x"></i>
                                                    </span>
                                                    <span
                                                        class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">
                                                        <?php echo Yii::t('app', 'Language'); ?></span>
                                                </a>
                                            </div>
                                            <!--end:Item-->
                                        </div>
                                        <!--end:Nav-->
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--begin::User-->
                                <div class="dropdown">
                                    <!--begin::Toggle-->
                                    <div class="topbar-item">
                                        <div class="btn btn-icon btn-hover-transparent-white d-flex align-items-center btn-lg px-md-2 w-md-auto"
                                            id="kt_quick_user_toggle">
                                            <span
                                                class="text-white opacity-70 font-weight-bold font-size-base d-none d-md-inline mr-1"><?=Yii::t('app', 'Hi');?>,</span>
                                            <span
                                                class="text-white opacity-90 font-weight-bolder font-size-base d-none d-md-inline mr-4"><?=\Yii::$app->userData->formatName(\Yii::$app->user->id)?></span>
                                            <span class="symbol symbol-35">
                                                <img class="h-20px w-20px rounded-sm"
                                                    src="<?php echo \Yii::$app->userData->photo(\Yii::$app->user->id); ?>"
                                                    alt="" />
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Toggle-->
                                </div>
                                <!--end::User-->
                            </div>
                            <!--end::Topbar-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Content-->
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        <?php echo $content; ?>
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::Main-->
        <!-- begin::User Panel-->
        <div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
            <!--begin::Header-->
            <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
                <h3 class="font-weight-bold m-0"><?=Yii::t('app', 'My Profile');?>
                </h3>
                <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
                    <i class="ki ki-close icon-xs text-muted"></i>
                </a>
            </div>
            <!--end::Header-->
            <!--begin::Content-->
            <div class="offcanvas-content pr-5 mr-n5">
                <!--begin::Header-->
                <div class="d-flex align-items-center mt-5">
                    <div class="symbol symbol-100 mr-5">
                        <div class="symbol-label"
                            style="background-image:url('<?=Yii::$app->userData->photo(Yii::$app->user->id);?>')">
                        </div>
                        <i class="symbol-badge bg-success"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
                            <?=\Yii::$app->userData->formatName(\Yii::$app->user->id)?>
                        </a>
                        <div class="text-muted mt-1">
                            <?php echo Yii::$app->userData->role(Yii::$app->user->identity->email); ?></div>
                        <div class="navi mt-2">
                            <a href="#" class="navi-item">
                                <span class="navi-link p-0 pb-2">
                                    <span class="navi-text text-muted text-hover-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
                                                    fill="#000000"></path>
                                                <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5">
                                                </circle>
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="navi-text text-muted text-hover-primary">
                                        <?php echo Yii::$app->user->identity->email; ?></span>
                                </span>
                            </a>
                            <a data-method="post" href="<?php echo Url::to(['/site/logout']); ?>"
                                class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">
                                <?=Yii::t('app', 'Sign Out');?></a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator separator-dashed mt-8 mb-5"></div>
                <!--end::Separator-->
                <!--begin::Nav-->
                <div class="navi navi-spacer-x-0 p-0">
                    <!--begin::Item-->
                    <a href="<?php echo Url::to(['/account/index']); ?>" class="navi-item">
                        <div class="navi-link">
                            <div class="symbol symbol-40 bg-light mr-3">
                                <div class="symbol-label">
                                    <span class="svg-icon svg-icon-md svg-icon-success">
                                        <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/General/Notification2.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                                    fill="#000000" fill-rule="nonzero"
                                                    transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)">
                                                </path>
                                                <path
                                                    d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold"><?=Yii::t('app', 'My Profile');?></div>
                                <div class="text-muted"><?=Yii::t('app', 'Account settings & more');?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!--end:Item-->
                    <!--begin::Item-->
                    <a href="<?php echo Url::to(['/account/change-password']); ?>" class="navi-item">
                        <div class="navi-link">
                            <div class="symbol symbol-40 bg-light mr-3">
                                <div class="symbol-label">
                                    <span class="svg-icon svg-icon-md svg-icon-warning">
                                        <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Shopping/Chart-bar1.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z"
                                                    fill="#000000"></path>
                                                <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5">
                                                </circle>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold"><?=Yii::t('app', 'Change Password');?></div>
                                <div class="text-muted"><?=Yii::t('app', 'Update your login password');?></div>
                            </div>
                        </div>
                    </a>
                    <!--end:Item-->
                </div>
            </div>
            <!--end::Content-->
        </div>
    </body>
    <?php $this->endBody()?>
</body>

</html>
<?php $this->endPage()?>