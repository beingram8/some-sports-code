<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Season */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="season-form">

    <?php $form = ActiveForm::begin();?>

    <?php
echo $form->field($model, 'season')->widget(\etsoft\widgets\YearSelectbox::classname(), [
    'yearStart' => -2,
    'yearEnd' => 2,
]);
?>
    <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>
    <?php
echo $form->field($model, 'start_date')->widget(\kartik\date\DatePicker::classname(), [
    'type' => \kartik\date\DatePicker::TYPE_INPUT,

    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true,
    ],
]);
?>
    <?php
echo $form->field($model, 'end_date')->widget(\kartik\date\DatePicker::classname(), [
    'type' => \kartik\date\DatePicker::TYPE_INPUT,

    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true,
    ],
]);
?>

    <?=$form->field($model, 'is_expired')->dropDownList(\Yii::$app->general->tinyForYes())?>

    <div class="form-group">
        <?=Html::submitButton('Save', ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveForm::end();?>

</div>