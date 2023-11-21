<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InterfaceConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interface-configuration-form">

    <?php $form = ActiveForm::begin();?>
    <div class="row">
        <div class="col-md-3">
            <?=$form->field($model, 'central_photo')->fileInput()?>
            <div class="symbol symbol-60 symbol-xxl-150 align-self-start align-self-xxl-center">
                <div class="symbol-label"
                    style="background-image:url('<?=Yii::$app->general->img($model->central_photo);?>')">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'logo')->fileInput()?>
            <div class="symbol symbol-60 symbol-xxl-150 align-self-start align-self-xxl-center">
                <div class="symbol-label" style="background-image:url('<?=Yii::$app->general->img($model->logo);?>')">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'primary_bg_color')->textInput(['maxlength' => true, 'type' => 'color'])?>
        </div>
    </div>



    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'google_play_icon')->textInput(['maxlength' => true])?>
        </div>

        <div class="col-md-6">
            <?=$form->field($model, 'apple_store_icon')->textInput(['maxlength' => true])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'header_text')->textInput();?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'footer_text')->textInput();?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'how_it_work')->widget(\dosamigos\tinymce\TinyMce::className(), [
    'options' => ['rows' => 5],
    'language' => 'en',
    'clientOptions' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste",
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    ],
]);?>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6">

            <?=$form->field($model, 'disclaimer')->widget(\dosamigos\tinymce\TinyMce::className(), [
    'options' => ['rows' => 5],
    'language' => 'en',
    'clientOptions' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste",
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    ],
]);?>
        </div>
        <div class="col-md-6">

            <?=$form->field($model, 'appointment_instruction')->widget(\dosamigos\tinymce\TinyMce::className(), [
    'options' => ['rows' => 5],
    'language' => 'en',
    'clientOptions' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste",
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    ],
]);?>
        </div>
    </div>

    <div class="form-group">
        <?=Html::submitButton('Save', ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveForm::end();?>

</div>