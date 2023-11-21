<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserMatchVoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Match Voting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="col-xl-10 w-100">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
                        <div class="px-2 py-3">
                            <h3 class="card-label mt-5">Voting | Match ID
                                <small><?=$searchModel->match->id;?> | <?=$searchModel->match->api_match_id;?></small>
                            </h3>
                        </div>
                        <div class="card-toolbar px-3">
                            <?php if ($searchModel->user_id) {?>
                            <?=\yii\helpers\Html::a(
    Yii::t('app', '<< Back'),
    ['match/user-voted-match', 'SeasonMatchSearch[user_id]' => $searchModel->user_id],
    ['data-pjax' => '0', 'class' => 'btn btn-primary font-weight-bold py-3 px-6']
)?>
                            <?php } else {?>
                            <?=\yii\helpers\Html::a(
    Yii::t('app', '<< Back'),
    ['match/index'],
    ['data-pjax' => '0', 'class' => 'btn btn-primary font-weight-bold py-3 px-6']
)?>
                            <?php }?>
                        </div>
                    </div>
                    <div class="px-10 py-3">
                        <div style="display:flex; justify-content: space-between;">
                            <div>
                                <p class="font-size-h6 font-weight-bold">
                                    <?=date('Y-m-d H:i:s', $searchModel->match->match_timestamp) . ' <span class="font-size-xs">(GMT + 2)</span>';?>
                                </p>
                            </div>
                            <div>
                                <p style="margin-right:190px;" class=" font-size-h6 font-weight-bold">
                                    <?=$searchModel->match->league->name;?></p>
                            </div>
                            <div>
                                <p class="font-size-h6 font-weight-bold"><?=$searchModel->match->season;?></p>
                            </div>
                        </div>
                        <div style="display:flex; justify-content: space-between;">
                            <div>
                                <span class="navi-icon">
                                    <span class="svg-icon svg-icon-lg">
                                        <div class="symbol symbol-40 ">
                                            <div class="symbol-label"
                                                style="background-image: url(<?=$searchModel->match->teamHome->logo;?>)">
                                            </div>
                                        </div>
                                    </span>
                                </span>
                                <p style="text-align:center;" class="font-weight-bold">
                                    <?=$searchModel->match->teamHome->name;?>
                                </p>
                            </div>
                            <div>
                                <span style="display:flex;justify-content:center;">
                                    <h3 class="display-5 font-weight-bold"> <?=$searchModel->match->goal_of_home_team;?>
                                    </h3>
                                    <h3 class="display-5 font-weight-bold"> :
                                        <?=$searchModel->match->goal_of_away_team;?></h3>
                                </span>
                                <?=$searchModel->match->match_ground;?></p>
                            </div>
                            <div>
                                <span class="navi-icon">
                                    <span class="svg-icon svg-icon-lg">
                                        <div class="symbol symbol-40 ">
                                            <div class="symbol-label"
                                                style="background-image: url(<?=$searchModel->match->teamAway->logo;?>)">
                                            </div>
                                        </div>
                                    </span>
                                </span>
                                <p style="text-align:center;" class="font-size-sm">
                                    <?=$searchModel->match->teamAway->name;?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::begin();?>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <?php echo Yii::$app->general->getFlash(); ?>
            <div class="card-body">
                <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'team_id',
            'headerOptions' => ['style' => 'width:15%'],
            'filter' => \Yii::$app->match->matchTeams($searchModel->match_id),
            'format' => 'html',
            'value' => function ($model) {
                return '<div class="symbol symbol-50">
                <div class="symbol-label" style="background-image:url(' . $model->team->logo . ')">

                </div>

                <p style="text-align:center;" class="font-size-sm">' . $model->team->name . '</p>
            </div>';
            },
        ],
        [
            'attribute' => 'player_id',
            'format' => 'html',
            'filter' => \Yii::$app->player->playerListForMatch($searchModel->match_id),
            'label' => 'Player Info',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle symbol-50 mr-3">
                        <img alt="Pic" src="' . $model->player->photo . '"></div>
                    <div class="d-flex flex-column">
                        <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . $model->player->name . '</a>
                    </div>
                </div>
            </div>';
                return $model->user->first_name . ' ' . $model->user->last_name;

            },
        ],
        [
            'attribute' => 'position',
            'format' => 'html',
            'filter' =>  ['D' => 'Defender', 'M' => 'Midfielder', 'G' => 'Goalkeeper', 'F' => 'Forward', 'Coach' => 'Coach'],
            'label' => 'Player Position',
            'value' => function ($model) {
                $player_info = \Yii::$app->player->playerInfo($model->player_id);
                return \Yii::$app->player->positionFullForm($player_info->position);

            },
        ],
        [
            'attribute' => 'vote',
            'headerOptions' => ['style' => 'width:10%'],
            'format' => 'raw',
            'value' => function ($model) {
                return '<div class="card-label">
                    <div class="font-weight-bolder">' . $model->vote . '</div>
                    <div class="font-size-sm text-muted mt-2">' . \Yii::$app->match->playerAvgVoteInMatch($model->match_id, $model->team_id, $model->player_id) . ' Avg vote</div>
                </div>';
            },
        ],
        [
            'attribute' => 'point',
            'label' => 'Point',
            'headerOptions' => ['style' => 'width:10%'],
            'format' => 'raw',
            'value' => function ($model) {
                return \Yii::$app->match->playerPointInMatch(
                    $model->match_id, $model->team_id, $model->user_id, $model->player_id);
            },
        ],

        [
            'attribute' => 'user',
            'format' => 'html',
            'label' => 'User Info',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle symbol-50 mr-3">
                        <img alt="Pic" src="' . Yii::$app->userData->photo($model->user->user_id) . '"></div>
                    <div class="d-flex flex-column">
                        <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . $model->user->first_name . ' ' . $model->user->last_name . '</a>
                        <span class="text-muted font-weight-bold font-size-sm">@' . $model->user->username . '</span>
                    </div>
                </div>
            </div>';
                return $model->user->first_name . ' ' . $model->user->last_name;

            },
        ],

        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'UserMatchVoteSearch[created_at]',
                'value' => $searchModel->created_at,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd M yyyy',
                    'endDate' => "0d",
                ],
                'options' => [
                    'autoComplete' => 'off',
                    'placeholder' => 'Select Created At',
                ],
            ]),
            'value' => function ($model) {
                return Yii::$app->general->format_date($model->created_at);
            },
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>