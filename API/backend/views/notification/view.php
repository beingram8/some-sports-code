<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Notification */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
            <?=Html::a(Yii::t('app', 'List'),
    ['index'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>

<div class="container">
    <div class="card card-custom card-stretch">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-title mb-3"><?=Yii::t('app', 'Notifications Details');?></h3>
            </div>
        </div>
        <div class="card-body">

    <?=DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'user_id',
        'uuid:ntext',
        'title',
        'message:ntext',
        'type',
        'data:ntext',
        [
            'attribute' => 'is_read',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->is_read == "s") {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                } elseif ($model->is_read == "N") {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'No') . '</span>';
                } else {
                    return '-';
                }
            },
        ],
        [
            'attribute' => 'push_completed',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->push_completed == 1) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'No') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Yii::$app->general->format_date($model->created_at);
            },
        ],
        'badge_count',
        'group_key',
        'push_request:ntext',
        'push_response:ntext',
    ],
])?>

        </div>
    </div>
</div>
