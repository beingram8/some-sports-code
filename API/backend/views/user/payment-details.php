<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Payment Details');
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
        <?php if(!empty($searchModel->user_id)) { ?>
        <div class="card-toolbar px-3">
            <?=\yii\helpers\Html::a(Yii::t('app', '<< Back'),
                ['user/index'], ['data-pjax' => '0', 'class' => 'btn btn-primary font-weight-bold py-3 px-6'])?>
        </div>
        <?php } ?>
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
        // ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
            'label' => 'amount',
            'attribute' => 'amount',
            'value' => function ($model) {
              return \Yii::$app->formatter->asCurrency($model->amount);
            },
        ],
        [
            'attribute' => 'status',
            'label' => 'Payment status',
            'format' => 'html',
            'filter' => ['' => 'All', 10 => 'Success', 9 => 'Pending', 20 => 'Failed'],
            'value' => function ($model) {
                if ($model->status == 10) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Success') . '</span>';
                } else if($model->status == 9) {
                    return '<span class="label label-warning label-pill label-inline mr-2">' . Yii::t('app', 'Pending') . '</span>';
                }else {
                  return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'Failed') . '</span>';
              }
            },
        ],
        'description',
        [
            'attribute' => 'created_at',
            'label' => 'Payment Date',
            'filter' => DatePicker::widget([
                'name' => 'UserPaymentTransactionSearch[created_at]',
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
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>