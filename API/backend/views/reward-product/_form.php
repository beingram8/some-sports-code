<?php

use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RewardProduct */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.thumbnail {
    height: 100px !important;
    margin-bottom: 25px !important;
    ;
}
</style>
<?php $form = ActiveForm::begin(['id' => 'form-profile']);?>

<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Product Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?=$form->field($model, 'reward_category_id')->widget(Select2::classname(), [
    'data' => \Yii::$app->reward->allCategory(),
    'language' => 'en',
    'name' => 'reward_category_id',
    'options' => ['placeholder' => 'Select Category'],
]);?>
                    </div>
                    <div class="col-md-3">
                        <?=$form->field($model, 'order_no')->textInput(['type' => 'number'])?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'description')->widget(TinyMce::className(), [
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
                    <div class="col-md-6">
                        <?=$form->field($model, 'reward_description')->widget(TinyMce::className(), [
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

                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'buying_token')->textInput()?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'reward_img_url')
    ->widget(\budyaga\cropper\Widget::className(), [
        'uploadUrl' => \yii\helpers\Url::toRoute('/image/upload?s3_folder=Reward-Product'),
        'width' => 750,
        'height' => 350,
        'maxSize' => 10000 * 1000,
        'extensions' => 'jpeg, jpg, png, gif',
    ]) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>