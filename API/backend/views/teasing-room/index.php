<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TeasingRoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Teasing Rooms');
$this->params['breadcrumbs'][] = $this->title;
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
    <?php Pjax::begin(['id' => 'pjax-grid-view']);?>
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom">
                <?php echo Yii::$app->general->getFlash(); ?>
                <div class="card-body">
    <?=GridView::widget([
    'id' => 'room-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'User Info',
            'format' => 'html',
            'attribute' => 'user_id',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50 mr-3">
                                    <img alt="Pic" src="' . Yii::$app->userData->photo($model->user_id) . '">
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . Yii::$app->userData->formatName($model->user_id) . '</a>
                                </div>
                            </div>
                        </div>';
            },
        ],
        [
            'attribute' => 'media',
            'format' => 'raw',
            'enableSorting' => false,
            'filter' => false,
            'value' => function ($model) {
                if($model->is_video == 1){
                    return '<a target="_blank" class="btn btn-sm btn-primary"  href="'.$model->media.'">Open link</a>';
                }
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->media . '">
                    </div>';
            },
        ],
        [
            'attribute' => 'caption',
            'format' => 'raw',
            'value' => function ($model) {
                if (!$model->caption) {
                    return '-';
                } else {
                    return '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#model' . $model->id . '">
                ' . Yii::t('app', 'Read Caption') . '
                    </button>
                    <div class="modal" id="model' . $model->id . '" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="model Label">' . \Yii::t('app', 'View Caption') . '</h5>
                                </div>
                                <div class="modal-body">
                                    ' . $model->caption . '
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">' . \Yii::t('app', 'Close') . '</button>

                                </div>
                            </div>
                        </div>
                    </div>';
                    return $model->caption;
                }
            },
        ],
        [
            'attribute' => 'total_like',
            'label' => 'Total Like',
            'value' => function ($model) {
                return \common\models\TeasingRoomLike::find()->where(['teasing_id' => $model->id])->count();
            },
        ],
        [
            'attribute' => 'total_comment',
            'label' => 'Total Comment',
            'value' => function ($model) {
                return \common\models\TeasingRoomComment::find()->where(['teasing_id' => $model->id])->count();
            },
        ],
        [
            'attribute' => 'is_active',
            'format' => 'raw',
            'filter' => \Yii::$app->general->tinyForYes(),
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['teasing-room/update-room'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                            \yii\helpers\Html::dropDownList('is_active', $model->is_active, ['1' => 'Yes','0' => 'No'], ['prompt' => '', 'class' => 'room_status form-control', 'data-id' => $model->id, 'data-option' => $model->is_active]) . '
                        </form>';
            },
        ],
        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'TeasingRoomSearch[created_at]',
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
            'template' => '{report-list}{delete}',
            'buttons' => [
                'report-list' => function ($url, $model) {
                    return '<a data-pjax=0 href="' . Url::to(['teasing-room/report-list', 'TeasingReportSearch[teasing_id]' => $model->id]) . '" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="Report List">
                                <span class="svg-icon svg-icon-warning svg-icon-md">
                                    <i class="icon-l fas fa-vote-yea"></i>
                                </span>
                            </a>';
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

