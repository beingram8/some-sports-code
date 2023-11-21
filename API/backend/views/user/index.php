<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$levelData = Yii::$app->userData->getLevelList();
?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex flex-column breadcrumbs">
                <h2 class="text-white font-weight-bold my-2 mr-5">
                    <?=Html::encode($this->title)?>
                </h2>
                <?php echo Breadcrumbs::widget([
    'tag' => 'div',
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'itemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
    'options' => ['class' => 'd-flex m-left-8 align-items-center font-weight-bold my-2',
        'style' => "color: #fff;"],
    'activeItemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
]);
?>
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
        'id',
        [
            'label' => 'User Info',
            'format' => 'html',
            'attribute' => 'first_name',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50 mr-3">
                                    <img alt="Pic" src="' . Yii::$app->userData->photo($model->id) . '">
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . Yii::$app->userData->formatName($model->id) . '</a>
                                    <span class="text-muted font-weight-bold font-size-sm">' . $model->email . '</span>
                                </div>
                            </div>
                        </div>';
            },
        ],
        [
            'label' => 'Username',
            'attribute' => 'username',
            'value' => function ($model) {
                return $model->userData->username;
            },
        ],
        [
            'label' => 'Level',
            'attribute' => 'level_id',
            'filter' => $levelData,
            'value' => function ($model) use ($levelData) {
                return isset($levelData[$model->userData->level_id]) ? $levelData[$model->userData->level_id] : "";
            },
        ],
        [
            'label' => 'Supported Team',
            'format' => 'raw',
            'attribute' => 'team_id',
            'filter' => Yii::$app->team->getTeams(),
            'value' => function ($model) {
                if ($model->userData->team_id && isset($model->userData->team->logo)) {
                    return '<div class="flex text-center"><div>' . Yii::$app->img->showImage($model->userData->team->logo) . '</div><div>' . $model->userData->team->name . '</div></div>';
                } else {
                    return '-';
                }
            },
        ],
        [
            'attribute' => 'point',
            'format' => 'raw',
            'value' => function ($model) {
                return '<a href="' . Url::to(['user-point-transaction/index', 'UserPointTransactionSearch[user_id]' => $model->id]) . '" data-pjax=0 title="Point Detail">
                ' . $model->userData->point . '</a>';
            },
        ],
        [
            'attribute' => 'token',
            'format' => 'raw',
            'value' => function ($model) {
                return '<a href="' . Url::to(['user/transaction-detail', 'UserTokenTransactionSearch[user_id]' => $model->id]) . '" data-pjax=0 title="Transaction Detail">
                ' . $model->userData->token . '</a>';
            },
        ],
        [
            'attribute' => 'is_social',
            'label' => 'Is Social Account',
            'format' => 'html',
            'contentOptions' => ['style' => 'text-align: center;'],
            'filter' => \Yii::$app->general->tinyForYes(),
            'value' => function ($model) {
                if (!empty($model->userSocialData)) {
                    if ($model->userSocialData->provider_type == 1) {
                        return '<i class="text-primary icon-xl fab fa-facebook"></i><br><br>
                        <span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                    } else if ($model->userSocialData->provider_type == 2) {
                        return '<i class="text-danger icon-xl fab fa-google"></i><br><br>
                        <span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                    } else if ($model->userSocialData->provider_type == 3) {
                        return '<i class="icon-2x la text-dark-50 fab fa-apple"></i><br><br>
                        <span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                    }
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'No') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'status',
            'filter' => ['10' => 'Active', '9' => 'Disabled', '0' => 'Deleted'],
            'contentOptions' => ['style' => 'width:130px'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['user/update-status'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('status', $model->status, ['10' => 'Active', '9' => 'Disabled', '0' => 'Deleted'], ['prompt' => '', 'class' => 'status-dropdown form-control', 'data-id' => $model->id, 'data-option' => $model->status]) . '
                        </form>';
            },
        ],
        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'UserSearch[created_at]',
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
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{match}{view}{payment-details}{delete}',
            'buttons' => [
                'match' => function ($url, $model) {
                    return '<a data-pjax=0 href="' . Url::to(['match/user-voted-match', 'SeasonMatchSearch[user_id]' => $model->id]) . '" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="Voting List">
                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                    <i class="icon-l fas fa-vote-yea"></i>
                                </span>
                            </a>';
                },
                'payment-details' => function ($url, $model) {
                    return '<a data-pjax=0 href="' . Url::to(['user/payment-details', 'UserPaymentTransactionSearch[user_id]' => $model->id]) . '" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="Payment List">
                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                    <i class="icon-l fab fa-amazon-pay"></i>
                                </span>
                            </a>';
                },
                'view' => function ($url, $model) {
                    return \Yii::$app->general->icon('view', $url);
                },
                'delete' => function ($url, $model) {
                    return \Yii::$app->general->icon('delete', $url);
                },
            ],
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>