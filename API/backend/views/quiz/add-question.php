<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\Quiz */

$this->title = 'Add Quiz Question';
$this->params['breadcrumbs'][] = ['label' => 'Quiz', 'url' => ['index']];
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
        <?php echo Yii::$app->general->getFlash(); ?>
            <div class="">
            <?php $form = ActiveForm::begin(); ?>
                <div class="card card-custom bg-white gutter-b">
                    <div class="card-body pt-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-header pl-0 pb-0 mb-10">
                                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Quiz Question Form');?></h3>
                                </div>
                                <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'option_1')->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'option_2')->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'option_3')->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'option_4')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <?php if (!empty($model->correct_ans)) {
                                    if ($model->option_1 == $model->correct_ans) {
                                        $model->correct_ans = 'option_1';
                                    } else if ($model->option_2 == $model->correct_ans) {
                                        $model->correct_ans = 'option_2';
                                    } else if ($model->option_3 == $model->correct_ans) {
                                        $model->correct_ans = 'option_3';
                                    } else if ($model->option_4 == $model->correct_ans) {
                                        $model->correct_ans = 'option_4';
                                    }
                                } ?>
                                <?=$form->field($model, 'correct_ans')->dropDownList(['option_1' => 'Option 1', 'option_2' => 'Option 2', 'option_3' => 'Option 3', 'option_4' => 'Option 4'], ['prompt' => ['text' => 'Select Ans', 'options' => ['value' => '', 'disable' => true]]]);?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
                    </div>
                </div>
            <?php ActiveForm::end();?>
            </div>
            
        </div><br><br>
        <div class="card card-custom">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'question',
                        'option_1',
                        'option_2',
                        'option_3',
                        'option_4',
                        'correct_ans',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit-question}{delete-question}',
                            'buttons' => [
                                'edit-question' => function($url, $model){
                                    $url = Url::to(['quiz/add-question', 'quiz_id' => $model['quiz_id'], 'id' => $model['id']]);
                                    return \Yii::$app->general->icon('update', $url);
                                },
                                'delete-question' => function ($url, $model) {
                                    $url = Url::to(['quiz/remove-question', 'quiz_id' => $model['quiz_id'], 'id' => $model['id']]);
                                    return \Yii::$app->general->icon('delete', $url);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>

