<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $gridViewColumns array */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \yii2mod\rbac\models\search\AssignmentSearch */

$this->title = Yii::t('yii2mod.rbac', 'Assignments');
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
            <?=Html::a(Yii::t('app', 'Add Role'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' =>
    [
        [
            'attribute' => 'username',
            'label' => Yii::t('yii2mod.rbac', 'User'),
            'format' => 'html',
            'filter' => '<input type="text" class="form-control" name = "username" value="' . $searchModel->username . '">',
            'value' => function ($model) {
                return '<a href="#" class="font-weight-bold  text-dark-75 text-hover-primary">
                ' . \Yii::$app->userData->formatName($model->id) . '
                </a>
                <div class="text-muted mt-1">' . $model->email . '</div>';
            },
        ],
        [
            'attribute' => 'item_name',
            'label' => Yii::t('yii2mod.rbac', 'Role'),
            'filter' => \Yii::$app->userData->roles(),
            'value' => function ($model) {
                return Yii::$app->userData->role($model->id, true);
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<i class="fa fa-sm fa-eye" aria-hidden="true"></i>', $url,
                        ['class' => 'btn btn-sm btn-info'], [
                            'title' => Yii::t('app', 'View'),
                        ]);
                },
            ],
        ],
    ],
]); ?>
            </div>
        </div>
    </div>
</div>


<?php Pjax::end();?>