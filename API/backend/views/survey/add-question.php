<?php

use common\models\SurveyQuestion;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $model common\models\Survey */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Survey Question for ' . $model->survey->sponsored_by;
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => ['index']];
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
            <?=Html::a(Yii::t('app', 'List'),
    ['index'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']);?>
                <div class="card card-custom bg-white gutter-b">
                    <div class="card-body pt-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-header pl-0 pb-0 mb-10">
                                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Survey Question Form');?></h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?=$form->field($model, 'question')->textInput(['maxlength' => true])?>
                                    </div>
                                    
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <?=$form->field($model, 'option_text_1')->textInput(['value' => $model->id == 0 ? '' : SurveyQuestion::optionValues($model, 0)])?>
                                    </div>
                                    <div class="col-md-3">
                                        <?=$form->field($model, 'option_text_2')->textInput(['value' => $model->id == 0 ? '' : SurveyQuestion::optionValues($model, 1)])?>
                                    </div>
                                    <div class="col-md-3">
                                        <?=$form->field($model, 'option_text_3')->textInput(['value' => $model->id == 0 ? '' : SurveyQuestion::optionValues($model, 2)])?>
                                    </div>
                                    <div class="col-md-3">
                                        <?=$form->field($model, 'option_text_4')->textInput(['value' => $model->id == 0 ? '' : SurveyQuestion::optionValues($model, 3)])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><?=\Yii::t('app', 'Submit');?></button>
                    </div>
                </div>
            <?php ActiveForm::end();?>
        </div>
        <div class="card card-custom mt-8">
            <div class="card-body">
                <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'question',
        [
            'attribute' => 'option',
            'label' => 'Options',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->option_type == 0) {
                    $html = '';
                    foreach ($model->surveyQuestionOptions as $option) {
                        $html .= '<span class="label label-pill label-inline mt-2">' . $option->option_as_text . '</span><br>';
                    }
                    return $html;
                } else {
                    $html = '';
                    foreach ($model->surveyQuestionOptions as $option) {
                        $html .= '<div class="symbol symbol-40 symbol-sm flex-shrink-0 mr-2"><img class="" src="' . $option->option_as_img . '" alt="photo"></div>';
                    }
                    return $html;
                }
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Yii::$app->general->icon('update', 'add-question?survey_id=' . $model->survey_id . '&id=' . $model->id);
                },
                'delete' => function ($url, $model) {
                    return \Yii::$app->general->icon('delete', 'delete-question?id=' . $model->id);
                },
            ],
        ],
    ],
]);?>

            </div>
        </div>
    </div>
</div>
