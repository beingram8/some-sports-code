<?php

use common\models\NewsComment;
use common\models\NewsLike;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
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
            <?=Html::a(Yii::t('app', 'Create News'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
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
            'attribute' => 'thumb_img',
            'format' => 'html',
            'filter' => false,
            'value' => function ($model) {
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->thumb_img. '">
                    </div>';
            },
        ],
        [
            'attribute' => 'main_img',
            'format' => 'html',
            'filter' => false,
            'value' => function ($model) {
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->main_img. '">
                    </div>';
            },
        ],
        'title',
        // [
        //     'attribute' => 'small_description',
        //     'contentOptions' => ['style' => 'width:20%'],
        // ],
        [
            'attribute' => 'total_likes',
            'label' => 'Total Likes',
            'value' => function ($model) {
                return NewsLike::find()->where(['news_id' => $model->id])->count();
            },
        ],
        [
            'attribute' => 'total_comment',
            'label' => 'Total Comments',
            'value' => function ($model) {
                return NewsComment::find()->where(['news_id' => $model->id])->count();
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
            'filter' => DatePicker::widget([
                'name' => 'NewsSearch[created_at]',
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
                return Yii::$app->time->asDatetime($model->created_at);
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}{delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Yii::$app->general->icon('view', $url);
                },
                'update' => function ($url, $model) {
                    return Yii::$app->general->icon('update', $url);
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