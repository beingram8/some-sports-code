<?php

use dosamigos\tinymce\TinyMce;
use kartik\datetime\DateTimePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Quiz */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin();?>

<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Quiz Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'quiz_title')->textInput(['maxlength' => true])?>
                    </div>
                    <div class="col-md-3">
                        <?php
echo $form->field($model, 'start_date')->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Select Start date'],
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd hh:ii',
        'startDate' => date("Y-m-d"),
    ],
    'options' => [
        'autocomplete' => "off",
    ],
]); ?>
                    </div>
                    <div class="col-md-3">
                        <?php
echo $form->field($model, 'end_date')->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Select End date'],
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd hh:ii',
        'startDate' => date("Y-m-d"),
    ],
    'options' => [
        'autocomplete' => "off",
    ],
]); ?>
                    </div>
                </div>
                <?=$form->field($model, 'quiz_description')->widget(TinyMce::className(), [
    'options' => ['rows' => 6],
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
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>