<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CronScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =
Yii::t('app', 'Cron Schedules');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(); ?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column breadcrumbs">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">
                    <?= Html::encode($this->title) ?>
                </h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <?php                 echo Breadcrumbs::widget([
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
            <?= Html::a(Yii::t('app', 'Clear Log'),
            ['delete-all'], ['data-pjax'=>'0','class' => 'btn btn-white font-weight-bold py-3 px-6']) ?>

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <?php //                 echo $this->render('_search',['model' => $searchModel]); ?>

                <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'jobCode',
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:8%'],
                'filter' => \yii2mod\cron\models\enumerables\CronScheduleStatus::listData(),
                'format' => 'html',
                'filterInputOptions' => ['prompt' => Yii::t('app', 'Select Status'), 'class' => 'form-control'],
                'value' => function ($model) {
                    return \yii2mod\cron\models\enumerables\CronScheduleStatus::getLabel($model->status);
                },
            ],
            'messages:ntext',
            'dateCreated',
            'dateFinished',

                ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
                ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>