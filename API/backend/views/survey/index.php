<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Management';
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
            <?=Html::a(Yii::t('app', 'Create Survey'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>
<?php Pjax::begin(['id' => 'survey-grid']);?>
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

        'sponsored_by',
        [
            'attribute' => 'sponsor_adv',
            'format' => 'raw',
            'filter' => false,
            'value' => function ($model) {
                return '<div class="" >
                        <img style="height: 100px;" alt="Pic" src="' . $model->sponsor_adv. '">
                    </div>';
            },
        ],
        [
            'attribute' => 'is_active',
            'label' => 'Status',
            'filter' => ['0' => 'Pending', '1' => 'Started', '2' => 'Finished'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['survey/update-active'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_active', $model->is_active, ['0' => 'Pending', '1' => 'Started', '2' => 'Finished'], ['prompt' => '', 'class' => 'is-active-survey form-control', 'data-id' => $model->id, 'data-option' => $model->is_active]) . '
                                        </form>';
            },
        ],
        [
            'attribute' => 'start_date',
            'filter' => DatePicker::widget([
                'name' => 'SurveySearch[start_date]',
                'value' => $searchModel->start_date,
                'options' => ['placeholder' => 'Select Start date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
            ]),
            'value' => function ($model) {
                return \Yii::$app->time->asDatetime($model->start_date);
            },
        ],
        [
            'attribute' => 'end_date',
            'filter' => DatePicker::widget([
                'name' => 'SurveySearch[end_date]',
                'value' => $searchModel->end_date,
                'options' => ['placeholder' => 'Select End date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
            ]),
            'value' => function ($model) {
                return \Yii::$app->time->asDatetime($model->end_date);
            },
        ],
        [
            'attribute' => 'created_at',
            'filter' => false,
            'value' => function ($model) {
                return Yii::$app->time->asDatetime($model->created_at);
            },
        ],
        [
            'label' => 'Set Survey',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a('Set Survey', ['survey/add-question', 'survey_id' => $model['id']], ['class' => 'btn btn-success font-weight-bold btn-sm',
                    'data-pjax' => '0', 'title' => Yii::t('app', 'Add Survey'), 'aria-label' => 'Add Survey']);

            },
        ],
        [
            'label' => 'See Results',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a('See Results', ['survey/survey-result', 'id' => $model['id']], ['class' => 'btn btn-success font-weight-bold btn-sm',
                    'data-pjax' => '0', 'title' => Yii::t('app', 'See Results'), 'aria-label' => 'See Results']);
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Yii::$app->general->icon('update', $url);
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