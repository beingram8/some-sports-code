<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\QuizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quiz Management';
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
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'Create Quiz'),
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

        'quiz_title',
        [
            'attribute' => 'is_active',
            'label' => 'Quiz Status',
            'filter' => ['0' => 'Pending', '1' => 'Started', '2' => 'Finished'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['quiz/update-active'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_active', $model->is_active, ['0' => 'Pending', '1' => 'Active', '2' => 'Finished'], ['prompt' => '', 'class' => 'is-active-dropdown form-control', 'data-id' => $model->id, 'data-option' => $model->is_active]) . '
                                        </form>';
            },
        ],

        [
            'attribute' => 'start_date',
            'filter' => DatePicker::widget([
                'name' => 'QuizSearch[start_date]',
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
            'headerOptions' => ['width:10%'],
            'filter' => DatePicker::widget([
                'name' => 'QuizSearch[end_date]',
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
            'label' => 'Set Questions',
            'headerOptions' => ['width:10%'],
            'format' => 'html',
            'value' => function ($model) {
                return Html::a('Set Questions', ['quiz/add-question', 'quiz_id' => $model['id']], ['class' => 'btn btn-success font-weight-bold btn-sm',
                    'data-pjax' => '0', 'title' => Yii::t('app', 'Add Questions'), 'aria-label' => 'Add Questions']);
            },
        ],
        [
            'attribute' => 'id',
            'header' => 'Quiz Winner',
            'filter' => false,
            'headerOptions' => ['style' => 'width:15%'],
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->is_active != 0) {
                    return '<a class="btn btn-success btn-sm font-weight-bold" href="' . Url::to(['winner/quiz-winner', 'QuizWinnerSearch[quiz_id]' => $model->id], $schema = true) . '" data-pjax="0">See Winners</a>';
                } else {
                    return 'Pending';
                }
            },
        ],
        ['attribute' => 'created_at', 'value' => function ($model) {
            return \Yii::$app->time->asDatetime($model->created_at);
        }],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'buttons' => [

                'update' => function ($url, $model) {
                    return Yii::$app->general->icon('update', $url);
                },
                'delete' => function ($url, $model) {
                    if ($model->is_active == 0) {
                        return \Yii::$app->general->icon('delete', $url);
                    }
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