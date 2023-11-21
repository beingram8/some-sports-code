<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin();?>
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
            <?=Html::a(Yii::t('app', 'Create Video'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
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
            'attribute' => 'video_url',
            'format' => 'raw',
            'filter' => false,
            'value' => function ($model) {
                if($model->is_external == 1){
                    return '<a class="btn btn-sm btn-primary" target="_blank" href="'.$model->external_link.'">Open link</a>';
                }
                return '<video width="150" height="150" controls>
                        <source src="' . $model->video_url . '" type="video/mp4">
                        </video>';
            },
        ],
        [
            'attribute' => 'thumb_img',
            'format' => 'raw',
            'filter' => false,
            'value' => function ($model) {
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->thumb_img. '">
                    </div>';
            },
        ],

        'title',
        [
            'attribute' => 'description',
            'headerOptions' => ['style' => 'width:10%'],
            'format' => 'raw',
            'filter' => false,
            'value' => function ($model) {
                return '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#model' . $model->id . '">
                ' . Yii::t('app', 'Read Description') . '
            </button>
            <div class="modal" id="model' . $model->id . '" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="model Label">' . \Yii::t('app', 'View Description') . '</h5>
                        </div>
                        <div class="modal-body">
                            ' . $model->description . '
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">' . \Yii::t('app', 'Close') . '</button>

                        </div>
                    </div>
                </div>
            </div>';
            },
        ],
        // [
        //     'attribute' => 'description',
        //     'format' => 'raw',
        //     'value' => function ($model) {
        //         return '<button type="button" class="btn btn-primary btn-sm video_modal" data-id="' . $model->description . '" data-toggle="modal" >
        //                         View Description
        //                     </button>';
        //     },
        // ],

        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'VideoSearch[created_at]',
                'value' => $searchModel->created_at,
                'options' => ['placeholder' => 'Select Created At'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd M yyyy',
                    'endDate' => "0d",
                ],
            ]),
            'value' => function ($model) {
                return Yii::$app->general->format_date($model->created_at);
            },
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'buttons' => [
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

<div class="modal fade" id="video_desc_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="video_descs"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>