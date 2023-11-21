<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
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
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'Create Notification'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                    <?php Pjax::begin();?>
                        <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'user_id',
            'format' => 'raw',
            'label' => 'User Name',
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
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return '<div class="py-9">
                            <div class="d-flex align-items-center mb-2">
                                <span class="font-weight-bolder mr-2">Title:</span>
                                <span>' . $model->title . '</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="font-weight-bolder mr-2">Message:</span>
                                <span>' . $model->message . '</span>
                            </div>
                        </div>';
            },
        ],
        [
            'attribute' => 'type',
            'filter' => ['admin' => 'Admin', 'vote' => 'Vote', 'quiz' => 'Quiz', 'survey' => 'Survey', 'news' => 'News', 'video' => 'Video', 'token' => 'Token', 'static' => 'Static', 'live_stream' => 'Live Stream', 'winner' => 'Winner', 'result' => 'Result'],
        ],
        [
            'attribute' => 'push_completed',
            'format' => 'raw',
            'filter' => [1 => 'Yes', 0 => 'No'],
            'value' => function ($model) {
                if ($model->push_completed) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'No') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'NotificationSearch[created_at]',
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
            'template' => '{view}{delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Yii::$app->general->icon('view', $url);
                },
                'delete' => function ($url, $model) {
                    return \Yii::$app->general->icon('delete', $url);
                },
            ],
        ],
    ],
]);?>
                    <?php Pjax::end();?>
                    </div>
                </div>
            </div>
        </div>
</section>
