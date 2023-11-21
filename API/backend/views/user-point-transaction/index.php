<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserPointTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Point Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin();?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex flex-column mb-6">
            <div class="container">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                        <div class="symbol-label"
                            style="background-image:url('<?=\Yii::$app->userData->photo($searchModel->user_id);?>')">
                        </div>
                    </div>
                    <div>
                        <a href="#"
                            class=" text-white font-weight-bolder font-size-h5"><?=\Yii::$app->userData->formatName($searchModel->user_id)?>(<?=\Yii::$app->userData->username($searchModel->user_id)?>)</a>
                        <div class="text-white">Total Points :
                            <?=\Yii::$app->userData->totalPoint($searchModel->user_id)?>
                        </div>
                        <div class="mt-2">
                            <a data-pjax=0 href="<?=Url::toRoute(['/user/view', 'id' => $searchModel->user_id])?>"
                                class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">View</a>
                            <a data-pjax=0 href="<?=Url::toRoute(['user/index']);?>"
                                class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">
                                << Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'Create User Point'),
    ['create', 'user_id' => $searchModel->user_id], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>
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
            'attribute' => 'type',
            'label' => 'Type',
            'format' => 'html',
            'filter' => ['1' => 'Match', '2' => 'Winning', '3' => 'Buying', '4' => 'By Admin'],
            'value' => function ($model) {
                if ($model->type == 1) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Match Point') . '</span>';
                } else if ($model->type == 2) {
                    return '<span class="label label-primary label-pill label-inline mr-2">' . Yii::t('app', 'Winning Point') . '</span>';
                } else if ($model->type == 3) {
                    return '<span class="label label-warning label-pill label-inline mr-2">' . Yii::t('app', 'Buying Point') . '</span>';
                } else {
                    return '<span class="label label-warning label-pill label-inline mr-2">' . Yii::t('app', 'By Admin') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'transaction_type',
            'format' => 'html',
            'filter' => ['1' => 'Credit', '0' => 'Debit'],
            'value' => function ($model) {
                if ($model->transaction_type == 1) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Credit') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'Debit') . '</span>';
                }
            },
        ],
        'points',
        [
            'attribute' => 'remark',
            'value' => function ($model) {
                return isset($model->remark) ? $model->remark : '-';
            },
        ],
        [
            'attribute' => 'match_id',
            'value' => function ($model) {
                return isset($model->match_id) ? $model->match_id : '-';
            },
        ],
        [
            'attribute' => 'team_id',
            'label' => 'Team',
            'filter' => \Yii::$app->team->getTeams(),
            'format' => 'html',
            'headerOptions' => ['style' => 'width:20%'],

            'value' => function ($model) {
                $team = \Yii::$app->team->getTeam($model->team_id);
                if (!empty($team['name'])) {
                    return '<div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center mr-2">
                        <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                            <div class="symbol-label">
                                <img src="' . $team['logo'] . '" alt="" class="h-50">
                            </div>
                        </div>
                        <div>
                            <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">' . $team['name'] . '</a>
                            <div class="font-size-sm text-muted font-weight-bold mt-1">' . $team['name'] . '</div>
                        </div>
                    </div>

                </div>';
                } else {
                    return '-';
                }
            },
        ],
        [
            'attribute' => 'player_id',
            'headerOptions' => ['style' => 'width:20%'],
            'format' => 'html',
            'value' => function ($model) {
                if ($model->player_id) {
                    return '<div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center mr-2">
                    <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                        <div class="symbol-label">
                            <img src="' . $model->player->photo . '" alt="" class="h-100 align-center">
                        </div>
                    </div>
                    <div>
                        <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">' . $model->player->name . '</a>
                    </div>
                </div>
            </div>';
                } else {
                    return '-';
                }
            },
        ],
        // [
        //     'class' => 'yii\grid\ActionColumn',
        //     'template' => '{update} {delete}',
        //     'buttons' => [
        //         'update' => function ($url, $model) {
        //             return Yii::$app->general->icon('update', $url);
        //         },
        //         'delete' => function ($url, $model) {
        //             return \Yii::$app->general->icon('delete', $url);
        //         },
        //     ],
        // ],
    ],
]);?>

            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>