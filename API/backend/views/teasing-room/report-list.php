<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TeasingRoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Teasing Room Reported List');
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
            'label' => 'Reported By',
            'format' => 'html',
            'attribute' => 'reported_user_id',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50 mr-3">
                                    <img alt="Pic" src="' . Yii::$app->userData->photo($model->reported_user_id) . '">
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . Yii::$app->userData->formatName($model->reported_user_id) . '</a>
                                </div>
                            </div>
                        </div>';
            },
        ],
        [
            'attribute' => 'media',
            'format' => 'html',
            'enableSorting' => false,
            'filter' => false,
            'value' => function ($model) {
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->teasing->media . '">
                    </div>';
            },
        ],
        [
            'attribute' => 'caption',
            'value' => function ($model) {
                if (!$model->teasing->caption) {
                    return '-';
                } else {
                    return $model->teasing->caption;
                }
            },
        ],
        'reason',
        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'TeasingReportSearch[created_at]',
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
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return \Yii::$app->general->icon('delete', 'report-delete?id=' . $model->id);
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

