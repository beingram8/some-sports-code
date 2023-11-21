<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =
Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin();?>
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
            <?=Html::a(Yii::t('app', 'Create User'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <?php //                 echo $this->render('_search',['model' => $searchModel]); ?>

                <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:8%'],
        ],
        [
            'attribute' => 'profile_pic',
            'headerOptions' => ['style' => 'width:5%'],
            'format' => 'html',
            'filter' => false,
            'value' => function ($model) {
                return '<div class="symbol symbol-50">
                <div class="symbol-label" style="background-image:url(' . \Yii::$app->userData->photo($model->id) . ')">
                </div>
            </div>';
            },
        ],
        'name',
        'surname',
        'email:email',
        [
            'attribute' => 'role',
            'headerOptions' => ['style' => 'width:10%'],
            'format' => 'html',
            'filter' => \Yii::$app->userData->roles(),
            'value' => function ($model) {
                return $model->role;
            },
        ],
        [
            'attribute' => 'status',
            'headerOptions' => ['style' => 'width:8%'],
            'filter' => ['' => 'All', 10 => \Yii::t('app', 'Active'), 9 => \Yii::t('app', 'Disable'), 0 => \Yii::t('app', 'Deleted')],
            'format' => 'html',
            'value' => function ($model) {
                if ($model->status == 10) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . \Yii::t('app', 'Active') . '</span>';
                } else if ($model->status == 9) {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . \Yii::t('app', 'Disable') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . \Yii::t('app', 'Deleted') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'credit',
            'headerOptions' => ['style' => 'width:10%'],
            'format' => 'html',
            'value' => function ($model) {
                return $model->credit ?
                '<span class="label label-success label-pill label-inline mr-2">' . $model->credit . '</span>' :
                '';
            },
        ],

        ['class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['style' => 'width:10%'],
            'template' => "{update} {delete} ",
            'buttons' => [
                'manage_credit' => function ($url, $model) {
                    return Html::a(
                        '<i class="fa fa-sm fa-database" aria-hidden="true"></i>',
                        ['credit/index', 'CreditSearch[user_id]' => $model->id],
                        ['class' => 'btn btn-sm btn-warning',
                            'data-toggle' => "popover",
                            'data-pjax' => 0,

                            'title' => Yii::t('app', 'Manage Credit'),
                            'data-content' => Yii::t('app', 'You can see all transaction of credits and also add or remove credits'),
                        ]
                    );
                },
                'update' => function ($url, $model) {
                    return Html::a('<i class="fa fa-sm fa-edit" aria-hidden="true"></i>', $url,
                        ['class' => 'btn btn-sm btn-primary'], [
                            'title' => Yii::t('app', 'update'),
                        ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<i class="fa fa-sm fa fa-trash" aria-hidden="true"></i>', $url,
                        [
                            'class' => 'btn btn-sm btn-danger',
                            'title' => Yii::t('app', 'delete'),
                            'data-method' => 'post',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure want to delete?'),
                            ],
                        ]);

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