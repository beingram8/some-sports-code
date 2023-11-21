<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */

$this->title = \Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column breadcrumbs">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">
                    <?=Html::encode($this->title)?>
                </h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <?php echo Breadcrumbs::widget([
    'tag' => 'div',
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'itemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
    'options' => ['class' => 'd-flex m-left-8 align-items-center font-weight-bold my-2',
        'style' => "color: #fff;"],
    'activeItemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
]);
?>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                <!--begin::Tiles Widget 12-->
                <div class="card card-custom gutter-b" style="height: 150px">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-success">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Communication/Group.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path
                                        d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    <path
                                        d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                        fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <div class="text-dark font-weight-bolder font-size-h2 mt-3"><?=$totalUsers?></div>
                        <a href="<?=Url::to(['user/index'])?>"
                            class="text-muted text-hover-primary font-weight-bold font-size-lg mt-1">Total Users</a>
                    </div>
                </div>
                <!--end::Tiles Widget 12-->
            </div>
            <div class="col-xl-3">
                <!--begin::Tiles Widget 12-->
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-success">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Communication/Group.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path
                                        d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    <path
                                        d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                        fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <div class="text-dark font-weight-bolder font-size-h2 mt-3"><?=$totalMatches?></div>
                        <a href="<?=Url::to(['match/index'])?>"
                            class="text-muted text-hover-primary font-weight-bold font-size-lg mt-1">Total Matches</a>
                    </div>
                </div>
                <!--end::Tiles Widget 12-->
            </div>
            <div class="col-xl-3">
                <div class="card card-custom gutter-b">
                    <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Quiz Attended by Users</h3>
                            <a href="<?=Url::to(['quiz/index', 'QuizSearch[id]' => $quiz['id']])?>"
                                class="text-dark-50 text-hover-primary font-size-lg mt-2 h3"><?=$quiz['quiz_title']?></a>
                        </div>
                        <span class="font-weight-bold py-3 px-6 h3"><?=!empty($quiz) ? $quiz['quiz_count'] : '-'?></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-custom gutter-b">
                    <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Survey Attended by Users</h3>
                            <a href="<?=Url::to(['survey/index', 'SurveySearch[id]' => $survey['id']])?>"
                                class="text-dark-50 text-hover-primary font-size-lg mt-2 h3"><?=$survey['sponsored_by']?></a>
                        </div>
                        <span class="font-weight-bold py-3 px-6 h3"><?=!empty($survey) ? $survey['survey_count'] : '-'?></span>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                            <h3 class="card-label">User Registration Graph</h3>
                        </div>
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                        aria-haspopup="true" aria-expanded="false">Filter By Year</a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        <?php if (isset($season)) {
    foreach ($season as $data) {?>
                                        <a class="dropdown-item" onclick=getMonthlyUsers(<?=$data?>)
                                            data-toggle="tab"><?=$data?></a>
                                        <?php }}?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="kt_tab_pane_1_2" role="tabpanel">
                                <div id="monthUser"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!--begin::Advance Table Widget 1-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header ">
                        <h3 class="card-title align-items-start flex-column mt-4">
                            <span class="card-label font-weight-bolder text-dark">Team Vs Supporters</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Each team with thier
                                support</span>
                        </h3>
                        <div class="card-toolbar">

                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body py-0">
                        <!--begin::Table-->
                        <div class="table-responsive h-400px">
                            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                                <tbody>
                                    <?php
$mainTeams = \Yii::$app->team->getTeamWithSupporters();
foreach ($mainTeams as $team) {
    ?>
                                    <tr>
                                        <td class="pr-0" style="width: 50px;">
                                            <div class="symbol symbol-50 symbol-light mt-1">
                                                <div class="symbol symbol-50">
                                                    <div class="symbol-label"
                                                        style="background-image:url('<?=$team['logo'];?>')">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pl-4">
                                            <a href="#"
                                                class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                                <?=$team['name'];?>
                                            </a>
                                        </td>
                                        <td class="pl-10 text-right">
                                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                <?=\Yii::$app->general->number_format_short($team['supporter']);?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
}?>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Advance Table Widget 1-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-10">
                <!--begin::Advance Table Widget 1-->
                <div class="card">
                    <!--begin::Header-->
                    <div class="card-header ">
                        <h3 class="card-title align-items-start flex-column mt-4">
                            <span class="card-label font-weight-bolder text-dark">Scheduled Cron</span>
                        </h3>
                        <div class="card-toolbar">
                            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                                <thead>
                                    <tr>
                                        <th class="pl-1">
                                            Command
                                        </th>
                                        <th class="pl-2">
                                            Scheduled Execution Time
                                        </th>
                                        <th class="pl-2">
                                            No of Execution Today
                                        </th>
                                        <th class="pl-2">
                                            Last Execute at
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pl-1">
                                            * * * * * /usr/bin/php /var/www/html/yii cron/manage-quiz-survey-status
                                        </td>
                                        <td class="pl-2">
                                            Every minutes
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/manage-quiz-survey-status'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/manage-quiz-survey-status'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            0 1 * * * /usr/bin/php /var/www/html/yii cron/remove-log </td>
                                        <td class="pl-2">
                                            Every day at 1:00 am
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/remove-log'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/remove-log'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            */10 * * * * /usr/bin/php /var/www/html/yii cron/close-voting
                                        </td>
                                        <td class="pl-2">
                                            Every 10 minutes
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/close-voting'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/close-voting'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            */10 * * * * /usr/bin/php /var/www/html/yii cron/finish-match
                                        </td>
                                        <td class="pl-2">
                                            Every 10 minutes
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/finish-match'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/finish-match'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            */10 * * * * /usr/bin/php /var/www/html/yii cron/fetch-substitue-if-remain
                                        </td>
                                        <td class="pl-2">
                                            Every 10 minutes
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/fetch-substitue-if-remain'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/fetch-substitue-if-remain'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            */30 * * * * /usr/bin/php /var/www/html/yii cron/point-calculation
                                        </td>
                                        <td class="pl-2">
                                            Every 30 minutes
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/point-calculation'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/point-calculation'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            */1 * * * * /usr/bin/php /var/www/html/yii cron/send-push-notification
                                        </td>
                                        <td class="pl-2">
                                            Every 1 minutes
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getNoOfExecution('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-10">
                <!--begin::Advance Table Widget 1-->
                <div class="card">
                    <!--begin::Header-->
                    <div class="card-header ">
                        <h3 class="card-title align-items-start flex-column mt-4">
                            <span class="card-label font-weight-bolder text-dark">Automatic Notifications</span>
                        </h3>
                        <div class="card-toolbar">
                            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                                <thead>
                                    <tr>
                                        <th class="pl-1">
                                            Type
                                        </th>
                                        <th class="pl-2">
                                            Total Notification Send
                                        </th>
                                        <th class="pl-2">
                                            No of Notification Today
                                        </th>
                                        <th class="pl-2">
                                            Last Execute at
                                        </th>
                                        <th class="pl-2">
                                            View Notification
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pl-1">
                                            Survey is Online
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['survey']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['survey']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2 display:flex; justify-content: space-between;">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'survey'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            Quiz is Online
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['quiz']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['quiz']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'quiz'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            Fan Rating stream is Live
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['stream']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['stream']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'live_stream'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            Vote is Open
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['vote']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['vote']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'vote'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            Winner in voting
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['winner']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['winner']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'winner'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            News is Live
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['news']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['news']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'news'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            Video is Uploaded
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['video']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['video']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'video'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pl-1">
                                            Match Result is out
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['result']['all']?>
                                        </td>
                                        <td class="pl-2">
                                            <?=$notification_count['result']['today']?>
                                        </td>
                                        <td class="pl-2">
                                            <?php echo \Yii::$app->cron->getLastExecutionTime('cron/send-push-notification'); ?>
                                        </td>
                                        <td class="pl-2">
                                            <a data-pjax="0" href="<?=Url::to(['notification/index', 'NotificationSearch[type]' => 'result'])?>" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View">
                                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                                    <i class="icon-l far fa-eye"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    getMonthlyUsers(<?=date('Y');?>);
})
</script>

<!-- <script src= "https://player.twitch.tv/js/embed/v1.js"></script>
<div id="test"></div>
<script type="text/javascript">
    const urlParams = new URLSearchParams(window.location.search);
    const runtest = urlParams.get('test');

    var options = {
        width: 800,
        height: 500,
        channel: "sahils20",
        allowfullscreen: false,
        layout: 'video-with-chat'
    };
    var player = new Twitch.Embed("test", options);

    if (runtest) {
        log('run test');
        player.addEventListener(Twitch.Embed.VIDEO_READY, function() {
        log('Attempt volumne and unmute');
        player.setVolume(0.1);
        player.setMuted(false);
        });
    }
</script> -->