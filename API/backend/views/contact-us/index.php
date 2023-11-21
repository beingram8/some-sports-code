<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactUsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contact-Us List';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['id' => 'quiz-grid']);?>
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
        'name',
        'email',
        'body:ntext',
        [
            'attribute' => 'status',
            'headerOptions' => ['style' => 'width:10%'],
            'filter' => ['0' => 'Pending', '1' => 'Closed'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['contact-us/update-status'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('status', $model->status, ['0' => 'Pending', '1' => 'Closed'], ['prompt' => '', 'class' => 'contact-us-status form-control', 'data-id' => $model->id, 'data-option' => $model->status]) . '
                                        </form>';
            },
        ],
        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'ContactUsSearch[created_at]',
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
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
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