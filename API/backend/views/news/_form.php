<?php

use common\models\Team;
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin();?>
<!--begin::Container-->
<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'News Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'title')->textInput(['maxLength' => true])?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'is_active')->dropDownList(['1' => 'Active', '0' => 'Disable'], ['prompt' => 'Select Status'])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'small_description')->textArea(['rows' => 5])?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'team')->widget(Select2::classname(), [
                            'data' => \Yii::$app->team->getTeams(),
                            'language' => 'en',
                            'name' => 'team[]',
                            'options' => ['placeholder' => 'Select Team', 'multiple' => true],
                        ]);?>
                        <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
                            <div class="alert-icon">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-text">If team is not selected, news will be mark as "general" by default.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?=$form->field($model, 'body')->widget(TinyMce::className(), [
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
                        <?php echo $form->field($model, 'main_img')
                        ->widget(\budyaga\cropper\Widget::className(), [
                            'uploadUrl' => \yii\helpers\Url::toRoute('/image/upload?s3_folder=news'),
                            'width' => 750,
                            'height' => 350,
                            'maxSize' => 10000 * 1000,
                            'extensions' => 'jpeg, jpg, png, gif',
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $form->field($model, 'thumb_img')
                        ->widget(\budyaga\cropper\Widget::className(), [
                            'uploadUrl' => \yii\helpers\Url::toRoute('/image/upload?s3_folder=news'),
                            'width' => 300,
                            'height' => 150,
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
        <button type="reset" class="btn btn-secondary"><?=\Yii::t('app', 'Reset');?></button>
    </div>
</div>
<?php ActiveForm::end();?>