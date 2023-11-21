<?php

use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Cms */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin();?>

<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Notification Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'message')->textInput(['maxlength' => true])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                    <?php
echo $form->field($model, 'team_id')->widget(\kartik\select2\Select2::classname(), [
    // 'initValueText' => $art_list, // array of text to show in the tag for the selected items
    'showToggleAll' => false,
    'options' => [
        'placeholder' => 'Search Team by name',
        'multiple' => true,
        'class' => 'validate',
    ],
    'pluginOptions' => [
        'tags' => false,
        'tokenSeparators' => [',', ' '],
        'allowClear' => true,
        'minimumInputLength' => 1,
        'ajax' => [
            'url' => \yii\helpers\Url::to(['/notification/teams']),
            'dataType' => 'json',
            'data' => new \yii\web\JsExpression('function(params) {return {t:params.term}; }'),
        ],
        'escapeMarkup' => new yii\web\JsExpression('function (markup) { return markup; }'),
        'templateResult' => new yii\web\JsExpression('function(data) {return data.text; }'),
        'templateSelection' => new yii\web\JsExpression('function (data) {  return data.text; }'),
    ],
])->label('Send to specific team users')->hint('If want to send notification to all team user then select team.')
?>
                    </div>
                    <h3 class="display-5 mt-10 ml-2 mr-2">OR</h3>
                    <div class="col-md-3">
                    <?php
echo $form->field($model, 'user_ids')->widget(\kartik\select2\Select2::classname(), [
    // 'initValueText' => $art_list, // array of text to show in the tag for the selected items
    'showToggleAll' => false,
    'options' => [
        'placeholder' => 'Search User by name',
        'multiple' => true,
        'class' => 'validate',
    ],
    'pluginOptions' => [
        'tags' => false,
        'tokenSeparators' => [',', ' '],
        'allowClear' => true,
        'minimumInputLength' => 1,
        'ajax' => [
            'url' => \yii\helpers\Url::to(['/notification/users']),
            'dataType' => 'json',
            'data' => new \yii\web\JsExpression('function(params) {return {q:params.term}; }'),
        ],
        'escapeMarkup' => new yii\web\JsExpression('function (markup) { return markup; }'),
        'templateResult' => new yii\web\JsExpression('function(data) {return data.text; }'),
        'templateSelection' => new yii\web\JsExpression('function (data) {  return data.text; }'),
    ],
])->label('Send to specific users')->hint('If want to send notification to all user then you do not need to select user.')
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button  type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>
