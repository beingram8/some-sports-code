<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonMatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\widgets\Pjax;
$this->title =
Yii::t('app', 'Season Matches');
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

                <?php //                 echo $this->render('_search',['model' => $searchModel]); ?>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <?php echo $this->render('_fetch_fixture', ['model' => $model]); ?>
            </div>
        </div>
        <br>
        <?php if ($fixtures) {?>
        <div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample3">
            <div class="card">
                <div class="card-header" id="headingOne3">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true">
                        Available Matches
                    </div>
                </div>
                <div id="collapseOne3" class="collapse show" data-parent="#accordionExample3" style="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12" style="overflow:hidden">
                                <div id="fixtures" class="row" style="height:400px;overflow-x:scroll">
                                    <?php echo $this->render('response/fixtures', ['fixtures' => $fixtures, 'model' => $model]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>

        <?php }?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="text-white fa fa-check"></i>Match Updated!</h4>
            <?=Yii::$app->session->getFlash('success')?>
        </div>
        <?php endif;?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="text-white fa fa-check"></i>Oops!</h4>
            <?=Yii::$app->session->getFlash('error')?>
        </div>
        <?php endif;?>
        <div class="row mb-10">
            <?php
// Finished
if ($searchModel->is_point_calculated) {
    $all_matches = "btn-secondary";
    $upcoming_matches = "btn-secondary";
    $ongoing_matches = "btn-secondary";
    $finished_matches = "btn-primary";
    //Progressive
} else if ($searchModel->is_vote_enabled == 1) {
    $all_matches = "btn-secondary";
    $upcoming_matches = "btn-secondary";
    $ongoing_matches = "btn-primary";
    $finished_matches = "btn-secondary";
} else if (isset($searchModel->is_vote_enabled) && $searchModel->is_vote_enabled == 0) {
    $all_matches = "btn-secondary";
    $upcoming_matches = "btn-primary";
    $ongoing_matches = "btn-secondary";
    $finished_matches = "btn-secondary";
} else {
    $all_matches = "btn-primary";
    $upcoming_matches = "btn-secondary";
    $ongoing_matches = "btn-secondary";
    $finished_matches = "btn-secondary";
}
?>
            <div class="col-md-3 ">
                <?=Html::a(Yii::t('app', 'All Matches'),
    ['index'], ['data-pjax' => '0', 'class' => 'btn btn-block ' . $all_matches . ' font-weight-bold py-3 px-6'])?>

            </div>
            <div class="col-md-3 ">
                <?=Html::a(Yii::t('app', 'Upcoming Matches'),
    ['index', 'SeasonMatchSearch[is_vote_enabled]' => 0],
    ['data-pjax' => '0', 'class' => 'btn btn-block ' . $upcoming_matches . ' font-weight-bold py-3 px-6'])?>

            </div>
            <div class="col-md-3 ">
                <?=Html::a(Yii::t('app', 'Ongoing Matches'),
    ['index', 'SeasonMatchSearch[is_vote_enabled]' => 1], ['data-pjax' => '0', 'class' => 'btn btn-block ' . $ongoing_matches . ' font-weight-bold py-3 px-6'])?>

            </div>
            <div class="col-md-3 ">
                <?=Html::a(Yii::t('app', 'Finished Matches'),
    ['index', 'SeasonMatchSearch[is_point_calculated]' => 1], ['data-pjax' => '0',
        'class' => 'btn btn-block ' . $finished_matches . ' font-weight-bold py-3 px-6'])?>

            </div>
        </div>
        <div class="card card-custom grid-overflow">
            <div class="card-body">
                <?php Pjax::begin(['id' => 'matches']);?>

                <?=GridView::widget([
    'id' => 'matches-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:3%'],
        ],
        [
            'attribute' => 'match_day',
            'contentOptions' => ['style' => 'width:6%'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['match/update-match-day'], $schema = true);
                // print_r($url);
                // die;
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::hiddenInput('id', $model->id) .
                \yii\helpers\Html::dropDownList('match_day', $model->match_day, \Yii::$app->general->matchDayArray(), ['prompt' => 'Select Matchday', 'class' => 'form-control match-day', 'data-id' => $model->id, 'data-option' => $model->match_day]) . '
                        </form>';
            },
        ],
        [
            'attribute' => 'api_match_id',
            'headerOptions' => ['style' => 'width:3%'],
        ],
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:20%'],
            'format' => 'raw',
            'value' => function ($model) {
                return "<div class='card px-5 py-5'>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <p class='font-size-xs'>" . \Yii::$app->time->asDatetime($model->match_timestamp) . "</p>
                                </div>
                                <div class='col-md-6'>
                                    <p class='text-center font-size-xs'>" . $model->league->name . "</p>
                                </div>
                                <div class='col-md-3'>
                                    <p class='font-size-xs'>" . $model->season . "</p>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <span class='navi-icon'>
                                        <span class='svg-icon svg-icon-lg'>
                                            <div class='symbol symbol-40 '>
                                                <div class='symbol-label'
                                                    style='background-image: url(" . $model->teamHome->logo . ")'>
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                    <p class='font-size-sm font-weight-bold'>
                                        " . $model->teamHome->name . "
                                    </p>
                                </div>
                                <div class='col-md-6'>
                                    <p class='text-center' style='font-size:25px;'>
                                        " . $model->goal_of_home_team . ":" . $model->goal_of_away_team . "</h3>
                                    </p>
                                    <p class='text-center font-size-xs'>
                                        " . $model->match_ground . "
                                    </p>
                                </div>
                                <div class='col-md-3'>
                                    <span class='navi-icon'>
                                        <span class='svg-icon svg-icon-lg'>
                                            <div class='symbol symbol-40 '>
                                                <div class='symbol-label' style='background-image: url(" . $model->teamAway->logo . ")'>
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                    <p class='font-size-sm font-weight-bold'>
                                        " . $model->teamAway->name . "
                                    </p>
                                </div>
                            </div>

                        </div>
                    <form class='row mt-5' action='" . \yii\helpers\Url::to(['match/add-url',
                    'id' => $model->id], $schema = true) . "'>
                        <div class='col-md-10'>
                            <input type='hidden' name='id' value='" . $model->id . "'>
                            <input name='match_url' value='" . $model->match_url . "'placeholder='Add Ticket URL' data-id='" . $model->id . "'type=\"text\" class=\"form-control url-text\">
                        </div>
                        <div class='col-md-2'>
                        <button type='submit'
                         class='btn btn-primary font-weight-bold modal-submit'>Set</button>
                        </div>
                    </form>";
            },
        ],

        [
            'attribute' => 'is_point_calculated',
            'filter' => [1 => 'Yes', '0' => 'No'],
            'headerOptions' => ['style' => 'width:1%'],
            'format' => 'html',
            'value' => function ($model) {
                if ($model->is_point_calculated == 1) {
                    return '<div class="label label-success label-inline font-weight-bold text-white py-4 px-3 font-size-base">Yes</div>';
                } else {
                    return '<div class="label label-warning label-inline font-weight-bold text-white py-4 px-3 font-size-base">No</div>';
                }
            },
        ],
        [
            'attribute' => 'is_vote_enabled',
            'filter' => [1 => 'Active', '2' => 'Closed', '0' => 'Pending'],
            'headerOptions' => ['style' => 'width:8%'],
            'format' => 'html',
            'value' => function ($model) {
                if ($model->is_vote_enabled == 1) {
                    return '<div class="label label-success label-inline font-weight-bold text-white py-4 px-3 font-size-base">Active</div>
<p>' . \Yii::$app->time->asDatetime($model->vote_closing_at) . ' (Close at)</p>';
                } else if ($model->is_vote_enabled == 2) {
                    return '<div class="label label-success label-inline font-weight-bold text-white py-4 px-3 font-size-base">Closed</div>
';
                } else {
                    return '<div class="label label-warning label-inline font-weight-bold text-white py-4 px-3 font-size-base">Pending</div>
';
                }
            },
        ],
        [
            'attribute' => 'is_match_finished',
            'filter' => \Yii::$app->general->tinyForYes(),
            'headerOptions' => ['style' => 'width:3%'],
            'format' => 'html',
            'value' => function ($model) {
                if ($model->is_match_finished == 1) {
                    return '<div class="label label-success label-inline font-weight-bold text-white py-4 px-3 font-size-base">Finished
</div>';
                } else if ($model->is_match_finished == -1) {
                    return '<div class="label label-danger label-inline font-weight-bold text-white py-4 px-3 font-size-base">Cancelled
</div>';
                } else {
                    return '<div class="label label-warning label-inline font-weight-bold text-white py-4 px-3 font-size-base">Not Finished
</div>';
                }
            },
        ],
        // [
        //     'attribute' => 'league_id',
        //     'headerOptions' => ['style' => 'width:10%'],
        //     'filter' => \Yii::$app->league->getLeagues(),
        //     'value' => function ($model) {
        //         return $model->league->name;
        //     },
        // ],
        //         [
        //             'attribute' => 'team_home_id',
        //             'headerOptions' => ['style' => 'width:10%'],
        //             'filter' => \Yii::$app->team->getTeams(),
        //             'format' => 'html',
        //             'value' => function ($model) {
        //                 return '<div class="d-flex align-items-center mr-2">
        //     <div class="symbol symbol-40 symbol-light mr-3 flex-shrink-0">
        //         <div class="symbol-label">
        //             <img src="' . $model->teamHome->logo . '" alt="" class="h-50">
        //         </div>
        //     </div>
        //     <div>
        //         <a href="#" class="font-size-sm text-dark-75 text-hover-primary font-weight-bolder">' . $model->teamHome->name .
        //                     '</a>
        //     </div>
        // </div>';
        //             },
        //         ],
        //         [
        //             'attribute' => 'team_away_id',
        //             'headerOptions' => ['style' => 'width:10%'],
        //             'filter' => \Yii::$app->team->getTeams(),
        //             'format' => 'html',
        //             'value' => function ($model) {
        //                 return '<div class="d-flex align-items-center mr-2">
        //     <div class="symbol symbol-40 symbol-light mr-3 flex-shrink-0">
        //         <div class="symbol-label">
        //             <img src="' . $model->teamAway->logo . '" alt="" class="h-50">
        //         </div>
        //     </div>
        //     <div>
        //         <a href="#" class="font-size-sm text-dark-75 text-hover-primary font-weight-bolder">' . $model->teamAway->name .
        //                     '</a>
        //     </div>
        // </div>';
        //             },
        //         ],
        [
            'attribute' => 'id',
            'header' => 'Action',
            'filter' => false,
            'headerOptions' => ['style' => 'width:5%'],
            'format' => 'raw',
            'value' => function ($model) {

                return '
                <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="" data-placement="top" data-original-title="Quick actions">
                    <a href="#" class="btn btn-light-primary font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-outline-info icon-md"></i>Actions</a>
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right" style="">
                <!--begin::Navigation-->
                <ul class="navi navi-hover py-5">
                    <li class="navi-item">
                    <a href="' . \yii\helpers\Url::to(['update', 'id' => $model->id], $schema = true) . '"  data-pjax="0" class="navi-link">
                        <span class="navi-icon">
                            <i class="flaticon2-drop"></i>
                        </span>
                        <span class="navi-text">Update Match</span>
                    </a>

                    </li>
                    <li class="navi-item">
                        <a href="' . \yii\helpers\Url::to(['players', 'id' => $model->id], $schema = true) . '"  data-pjax="0" class="navi-link">
                            <span class="navi-icon">
                                <i class="flaticon2-drop"></i>
                            </span>
                            <span class="navi-text">Players</span>
                        </a>
                    </li>
                    <li class="navi-item">
                        <a href="' . \yii\helpers\Url::to(['/match-vote/vote-users', 'match_id' => $model->id], $schema = true) . '"  data-pjax="0" class="navi-link">
                            <span class="navi-icon">
                                <i class="flaticon2-drop"></i>
                            </span>
                            <span class="navi-text">Voters</span>
                        </a>
                    </li>
                    <li class="navi-item">
                        <a href="' . \yii\helpers\Url::to(['/match-vote/index', 'UserMatchVoteSearch[match_id]' => $model->id], $schema = true) . '"  data-pjax="0" class="navi-link">
                            <span class="navi-icon">
                                <i class="flaticon2-drop"></i>
                            </span>
                            <span class="navi-text">Match Voting</span>
                        </a>
                    </li>
                    <li class="navi-item">
                        <a href="' . \yii\helpers\Url::to(['/winner/index', 'SeasonMatchWinnerSearch[match_id]' => $model->id], $schema = true) . '"  data-pjax="0" class="navi-link">
                            <span class="navi-icon">
                                <i class="flaticon2-drop"></i>
                            </span>
                            <span class="navi-text">Fan Winners</span>
                        </a>
                    </li>

                </ul>
                <!--end::Navigation-->
            </div>
            </div>';
            },
        ],

    ],
]);?>
                <?php Pjax::end();?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-url-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Match Ticket URL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin()?>
                <div class="row">
                    <div class="col-md-12">
                        <?=$form->field($match, 'match_url')->textInput(['maxLength' => true, 'class' => 'form-control url-text', 'value' => ''])->label('Match Ticket URL')?>
                    </div>
                </div>
                <?php $form = ActiveForm::end()?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold modal-submit">Save</button>
            </div>
        </div>
    </div>
</div>