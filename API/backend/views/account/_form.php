<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin();?>
<!--begin::Container-->
<?php if (Yii::$app->session->hasFlash('success_profile')) {?>
<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
    <div class="alert-icon"><i class="fa fa-check"></i></div>
    <div class="alert-text"><?php echo Yii::$app->session->getFlash('success_profile'); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php }?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom  example example-compact">
            <div class="card-header">
                <h3 class="card-title"><?=Yii::t('app', 'Profile');?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <?=$form->field($model, 'photo')->fileInput()?>
                        <div class="symbol symbol-60 symbol-xxl-150 align-self-start align-self-xxl-center">
                            <div class="symbol-label"
                                style="background-image:url('<?=\Yii::$app->userData->photo(\Yii::$app->user->identity->id);?>')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3">
                                <?=$form->field($model, 'username')->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-3">
                                <?=$form->field($model, 'first_name')->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-3">
                                <?=$form->field($model, 'last_name')->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-3">
                            <?=$form->field($model, 'birth_date')->widget(DatePicker::classname(), [
    'model' => $model,
    'attribute' => 'birth_date',
    'options' => ['placeholder' => 'Select Birth date'],
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
        'endDate' => "0d",
    ],
]);?>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <?=$form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => true])?>
                            </div>
                            <div class="col-md-3">
                                <?=$form->field($model, 'gender')->radioList(['1' => 'Male', '2' => 'Female', '3' => 'Other'], ['labelOptions' => ['style' => 'margin-bottom:5px;']])?>
                            </div>
                            <div class="col-md-3">
                            <?=$form->field($model, 'city_id')->widget(Select2::classname(), [
    'data' => \common\models\UserCityList::allCity(),
    'language' => 'en',
    'name' => 'city_id',
    'options' => ['placeholder' => 'Select City'],
]);?>
                            </div>
                            <div class="col-md-3">
                                <?=$form->field($model, 'lang')->textInput(['maxlength' => true])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="">
            <button type="submit" class="btn btn-success mr-2"><?=\Yii::t('app', 'Submit');?></button>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>