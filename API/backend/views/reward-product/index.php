<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RewardProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reward Products';
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
            <?=Html::a(Yii::t('app', 'Create Product'),
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
            'attribute' => 'reward_img_url',
            'format' => 'html',
            'filter' => false,
            'value' => function ($model) {
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->reward_img_url. '">
                    </div>';
            },
        ],
        'name',
        'order_no',
        [
            'attribute' => 'reward_category_id',
            'label' => "Category",
            'filter' => \Yii::$app->reward->allCategory(),
            'format' => 'html',
            'value' => function ($model) {
                return Yii::$app->reward->getCategoryName($model->reward_category_id);;
            },
        ],
        
        'buying_token',
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
                            <h5 class="modal-title" id="model' . $model->id . 'Label">' . \Yii::t('app', 'View Description') . '</h5>
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
        [
            'attribute' => 'reward_description',
            'headerOptions' => ['style' => 'width:10%'],
            'format' => 'raw',
            'filter' => false,
            'value' => function ($model) {
                return '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#model1' . $model->id . '">
                ' . Yii::t('app', 'Read Description') . '
            </button>
            <div class="modal" id="model1' . $model->id . '" tabindex="-1" role="dialog" aria-labelledby="model' . $model->id . 'Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="model' . $model->id . 'Label">' . \Yii::t('app', 'View Reward Description') . '</h5>
                        </div>
                        <div class="modal-body">
                            ' . $model->reward_description . '
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">' . \Yii::t('app', 'Close') . '</button>

                        </div>
                    </div>
                </div>
            </div>';
            },
        ],
        

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{add-code}{view-code}{update}{delete}',
            'buttons' => [
                'add-code' => function ($url, $model) {
                    $icon = '<span class="svg-icon svg-icon-warning svg-icon-md">
                                                <i class="fa fa-plus"></i>
                                            </span>';
                    return '<a href="' . $url . '" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="Import Code">' . $icon . '</a>';
                },
                'view-code' => function ($url, $model) {
                    $icon = '<span class="svg-icon svg-icon-warning svg-icon-md">
                                                <i class="fa fa-eye"></i>
                                            </span>';
                    return '<a href="' . $url . '" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" title="View Code">' . $icon . '</a>';
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
