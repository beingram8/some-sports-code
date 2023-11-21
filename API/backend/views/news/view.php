<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = Yii::t('app', 'View News: {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' =>
    Yii::t('app', 'News'),
    'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
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
                <h3 class="card-title mb-3"><?=Yii::t('app', 'News Details');?></h3>
            </div>
        </div>
        <div class="card-body">
            <?=DetailView::widget([
    'model' => $model,
    'template' => "<tr><th style='width: 25%;'>{label}</th><td>{value}</td></tr>",
    'attributes' => [
        'id',
        'title',
        'small_description',
        [
            'attribute' => 'thumb_img',
            'format' => 'html',
            'value' => function ($model) {
                return Yii::$app->img->showImage($model->thumb_img);;
            },
        ],
        [
            'attribute' => 'main_img',
            'format' => 'html',
            'value' => function ($model) {
                return Yii::$app->img->showImage($model->main_img);
            },
        ],
        [
            'attribute' => 'is_active',
            'format' => 'html',
            'filter' => \Yii::$app->general->tinyForActive(),
            'value' => function ($model) {
                if ($model->is_active == 1) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Active') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'Disabled') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'is_general',
            'format' => 'html',
            'filter' => \Yii::$app->general->tinyForYes(),
            'value' => function ($model) {
                if ($model->is_general == 1) {
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
        [
            'attribute' => 'body',
            'format' => 'html',
            'value' => function ($model) {
                return $model->body;
            },
        ],
    ],
])?>
        </div>
    </div>
</div>
<?php Pjax::begin();?>
<div class="container mt-10">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch" style="height:auto;">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label"><?=\Yii::t('app', 'News Comment')?>
                    </div>
                </div>
                <div class="card-body">
                    <?=GridView::widget([
    'dataProvider' => $commentProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'user_id',
            'label' => 'User Info',
            'format' => 'html',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-sm flex-shrink-0">
                                <img class="" src="' . Yii::$app->userData->photo($model->user_id) . '" alt=" User photo">
                            </div>
                            <div class="ml-4">
                                <div class="font-size-lg mb-0">' . Yii::$app->userData->formatName($model->user_id) . '</div>
                                <p class="text-muted font-weight-bold text-hover-primary">' . $model->user->user->email . '</p>
                            </div>
                        </div>';
            },
        ],
        [
            'attribute' => 'comment_text',
            'contentOptions' => ['style' => 'width:50%'],
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Commented At',
            'value' => function ($model) {
                return Yii::$app->general->format_date($model->created_at);
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return '<a class=""
                     href="' . Url::to(['/news/delete-comment', 'comment_id' => $model->id],
                        $schema = true) . '"><i class="fa fa-trash"></a></a>';
                },

            ],
        ],
    ],
]);?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom card-stretch" style="height:auto;">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label"><?=\Yii::t('app', 'News Like')?>
                    </div>
                </div>
                <div class="card-body">
                    <?=GridView::widget([
    'dataProvider' => $likeProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'user_id',
            'label' => 'User Info',
            'format' => 'html',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-sm flex-shrink-0">
                                <img class="" src="' . Yii::$app->userData->photo($model->user_id) . '" alt=" User photo">
                            </div>
                            <div class="ml-4">
                                <div class="font-size-lg mb-0">' . Yii::$app->userData->formatName($model->user_id) . '</div>
                                <p class="text-muted font-weight-bold text-hover-primary">' . $model->user->user->email . '</p>
                            </div>
                        </div>';
            },
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Liked At',
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
</div>
<?php Pjax::end();?>