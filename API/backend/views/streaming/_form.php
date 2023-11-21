<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Streaming */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin();?>
<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Streaming Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?=$form->field($model, 'title')->textInput(['maxLength' => true])?>
                    </div>
                    <div class="col-md-2">
                        <?=$form->field($model, 'is_live')->dropDownList(['1' => 'Yes', '0' => 'No'], ['prompt' => 'Select Option', 'class' => 'form-control stream-option'])?>
                    </div>
                    <div class="col-md-2">
                        <?=$form->field($model, 'is_external')->dropDownList(['2' => 'Yes', '1' => 'No'], ['prompt' => 'Select Option', 'class' => 'form-control stream-option'])?>
                    </div>
                    <div class="col-md-3 <?php
if ($model->is_external == 1) {
    echo 'show';
} else {
    echo 'hide';
}
?>
                    " id="video-input-div">
                        <?=$form->field($model, 'video')->fileInput()?>
                        <?php if (!empty($model->video)) {?>
                            <video width="150" height="150" controls>
                                <source src="<?=$model->video?>" type="video/mp4">
                            </video>
                        <?php }?>
                    </div>
                    <div class="col-md-3 <?php
if ($model->is_external == 2) {
    echo 'show';
} else {
    echo 'hide';
}
?>" id="external-link-input-div">
                        <?=$form->field($model, 'external_link')->textInput()?>
                    </div>
                </div>

                <div class="col-md-4">
                        <?php echo $form->field($model, 'thumb_img')
    ->widget(\budyaga\cropper\Widget::className(), [
        'uploadUrl' => \yii\helpers\Url::toRoute('/image/upload?s3_folder=stream'),
        'width' => 750,
        'height' => 350,
        'maxSize' => 10000 * 1000,
        'extensions' => 'jpeg, jpg, png, gif',
    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary'])?>
        <button type="reset" class="btn btn-secondary"><?=\Yii::t('app', 'Reset');?></button>
    </div>
</div>
<?php ActiveForm::end();?>

<script>
$('#streaming-is_external').change(function(){
    var optionValue = $(this).val();
    console.log(optionValue);

    if(optionValue == 2){
        $('#external-link-input-div').removeClass('hide');
        $('#video-input-div').addClass('hide');
    } else {
        $('#video-input-div').removeClass('hide');
        $('#external-link-input-div').addClass('hide');
    }
});

</script>