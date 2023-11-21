<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \yii2mod\rbac\models\search\AuthItemSearch */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column breadcrumbs">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">
                    <?=Html::encode($this->title)?>
                </h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <?php echo Breadcrumbs::widget([
    'tag' => 'div',
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'itemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
    'options' => ['class' => 'd-flex m-left-8 align-items-center font-weight-bold my-2',
        'style' => "color: #fff;"],
    'activeItemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
]);
?>

                <?php //                 echo $this->render('_search',['model' => $searchModel]); ?>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'Create'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <?php Pjax::begin(['timeout' => 5000, 'enablePushState' => false]);?>
                <?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'label' => Yii::t('yii2mod.rbac', 'Name'),
        ],
        [
            'attribute' => 'ruleName',
            'label' => Yii::t('yii2mod.rbac', 'Rule Name'),
            'filter' => ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name'),
            'filterInputOptions' => ['class' => 'form-control', 'prompt' => Yii::t('yii2mod.rbac', 'Select Rule')],
        ],
        [
            'attribute' => 'description',
            'format' => 'ntext',
            'label' => Yii::t('yii2mod.rbac', 'Description'),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('yii2mod.rbac', 'Action'),
            'headerOptions' => ['style' => 'width:20%'],
            'template' => "{view} {update}",
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span><i class="fa fa-eye" aria-hidden="true"></i></span>', $url,
                        ['class' => 'btn btn-sm btn-info'], [
                            'title' => Yii::t('app', 'update'),
                        ]);
                },
                'update' => function ($url, $model) {
                    return Html::a('<span><i class="fa fa-edit" aria-hidden="true"></i></span>', $url, ['class' => 'btn btn-sm btn-primary'], [
                        'title' => Yii::t('app', 'update'),
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span><span><i class="fa fa-trash" aria-hidden="true"></i></span></span>', $url,
                        [
                            'class' => 'btn btn-sm btn-danger',
                            'title' => Yii::t('app', 'delete'),
                            'data-method'=>'POST',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure want to delete?'),
                            ],
                        ]);

                },
            ],
        ],
    ],
]); ?>
                <?php Pjax::end();?>

            </div>
        </div>
    </div>
</div>