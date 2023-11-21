<?php

use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;


/* @var $this yii\web\View */
/* @var $model common\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>


 <?php $form = ActiveForm::begin();?>

    <div class="card card-custom bg-white gutter-b">
        <div class="card-body pt-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header pl-0 pb-0 mb-10">
                        <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Video Form');?></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>
                        </div>
                        <div class="col-md-3">
                            <?=$form->field($model, 'is_external')->dropDownList(['1' => 'Yes', '0' => 'No'], ['prompt' => 'Select Option', 'class' => 'form-control is_external'])?>
                        </div>
                        <div class="row">
                            <div class="video-div1 <?php
                                if (empty($model->is_external)) {
                                    echo "hide";
                                } elseif ($model->is_external == 1) {
                                    echo "show";
                                }
                                ?>">
                                <div class="col-md-12">
                                    <?=$form->field($model, 'external_link')->textInput()?>
                                </div>
                            </div>
                            <div class="video-div <?php
                                if (empty($model->video_url) || $model->is_external == 1) {
                                    echo "hide";
                                } elseif (empty($model->video_url) && $model->is_external == 0) {
                                    echo "show";
                                }
                                ?>">
                                <div class="col-md-3">
                                    <?=$form->field($model, 'video_url')->fileInput()?>
                                        <?php if (!empty($model->video_url)) {?>
                                        <video width="150" height="150" controls>
                                            <source src="<?=$model->video_url?>" type="video/mp4">
                                        </video>
                                        <?php }?>
                                </div>
                            </div>
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
                            <?php echo $form->field($model, 'thumb_img')
                            ->widget(\budyaga\cropper\Widget::className(), [
                                'uploadUrl' => \yii\helpers\Url::toRoute('/image/upload?s3_folder=videos'),
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
        <button  type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>


<script>
$('.is_external').change(function(){
    var optionValue = $(this).val();
    console.log(optionValue);
    if(optionValue == 1){
        $('.video-div').addClass('hide');
        $('.video-div1').removeClass('hide');
        $('.video-div1').addClass('show');
    } else {
        $('.video-div').removeClass('hide');
        $('.video-div1').addClass('hide');
    }
});

</script>
