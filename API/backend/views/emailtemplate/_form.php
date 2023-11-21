<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\tinymce\TinyMce;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model common\models\EmailTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin();?>

<div class="">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'emai_template_name')->textInput(['maxlength' => true,'oninput'=>'myFunction()']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email_slug')->textInput(['disabled'=>true]);?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email_status')->dropDownList([ 'active' => 'Active', 'deactive' => 'Deactive', ], ['prompt' => '']) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'email_subject')->textInput(['maxlength' => true,'oninput'=>'myFunction()']) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'email_content')->widget(TinyMce::className(), [
                'options' => ['rows' => 12],
                'language' => 'en',
                'clientOptions' => [
                    'force_br_newlines' => true,
                    'force_p_newlines' => false,
                    'forced_root_block' => '',
                    'relative_urls' => false,
                    'remove_script_host' => false,
                    'convert_urls' => false,
                    'images_dataimg_filter'=>  new JsExpression("function(img){
                        console.log(img);
                        return img.hasAttribute('internal-blob');
                    }"),
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste image imagetools"
                ],
                "imagetools_toolbar"=>"editimage",
                
                // 'menubar'=> ["insert"],
                'automatic_uploads' => true,
                'file_picker_types'=> 'image',
                'image_caption'=>true,
                'images_upload_url'=> Url::base(true).'/email/default/uploadimage',
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image imageupload | fontselect | cut copy paste"
            ]
            ]); ?>
        </div>
    </div>
</div>
<div class="card-footer">
    <?= $form->field($model, 'email_slug')->hiddenInput()->label(false); ?>
    <button type="submit" class="btn btn-primary mr-2"><?php echo Yii::t('app', 'Submit'); ?></button>
    <button type="reset" class="btn btn-secondary"><?php echo Yii::t('app', 'Reset'); ?></button>
</div>
<?php ActiveForm::end(); ?>
<?php

$flag = 'http';
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
	$flag = 'https';
}
#$this->registerJS(
#    "var baseUrl = '".Url::base('https')."'"
#);

$this->registerJS(
    "$(document).ready(function() {
        $(document).on('click', '.dummyCntent', function(e) {
            $.ajax({
                type: 'POST',
                url: baseUrl + '/email/default/getcontent',
                data: {},
                cache: false,
                dataType: 'json',
                success: function(data) {
                    tinymce.get('emailtemplate-email_content').setContent(data.html);
                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
        })
    })"
);
?>