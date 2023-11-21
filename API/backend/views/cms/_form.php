<?php

use dosamigos\tinymce\TinyMce;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
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
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Cms Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'title')->textInput(['maxlength' => true])?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'status')->dropDownList(['1' => 'Active', '0' => 'Disable'], ['prompt' => 'Select Status'])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <?=$form->field($model, 'slug')->textInput(['maxlength' => true])?>
                    </div>
                    <div class="col-md-6">
                    <?=$form->field($model, 'language')->widget(Select2::classname(), [
                        'data' => \Yii::$app->lang->getLanguages(),
                        'language' => 'en',
                        'name' => 'language',
                        'options' => ['placeholder' => 'Select Language'],
                    ]);?>
                    </div>
                </div>

                <?php echo $form->field($model, 'html_body')->widget(\yii2mod\markdown\MarkdownEditor::class, [
    'editorOptions' => [
        'showIcons' => ["code", "table"],
    ],
]); ?>

                <?=$form->field($model, 'app_body')->widget(TinyMce::className(), [
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
        <button  type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>
