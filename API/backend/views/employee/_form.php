<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin();?>
<!--begin::Container-->
<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-6" style="border-right:1px solid #ddd">
                <div class="">
                    <div class="card-header pl-0 pb-0 mb-10">
                        <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'General Data');?></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?=$form->field($model, 'profile_pic')->fileInput()?>
                            <div class="symbol symbol-60 symbol-xxl-150 align-self-start align-self-xxl-center">
                                <div class="symbol-label"
                                    style="background-image:url('<?=Yii::$app->general->img($model->profile_pic);?>')">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
                                </div>
                                <div class="col-md-6">
                                    <?=$form->field($model, 'surname')->textInput(['maxlength' => true])?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->field($model, 'phone_code')
    ->widget(\kartik\select2\Select2::classname(), [
        'data' => \Yii::$app->employee->phoneCode(),
        'options' => ['placeholder' => \Yii::t('app', 'Please Select'), 'multiple' => false],
    ]);
?>
                                </div>
                                <div class="col-md-6">
                                    <?=$form->field($model, 'phone')->textInput(['maxlength' => true])?>
                                </div>
                                <div class="col-md-6">
                                    <?=$form->field($model, 'lang_code')->dropDownList(\Yii::$app->lang->getList())?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <?=$form->field($model, 'status')->dropDownList([10 => \Yii::t('app', 'Active'), 9 => \Yii::t('app', 'Disable'), 0 => \Yii::t('app', 'Deleted')], ['prompt' => \Yii::t('app', 'Please select')])?>
                        </div>
                        <div class="col-md-6">
                            <?=$form->field($model, 'address_type')->dropDownList(['tax_address' => 'Tax Address', 'billing_address' => 'Billing Address', 'address' => 'Address'], ['prompt' => 'Select Address Type']);?>
                        </div>
                        <div class="col-md-12">
                            <?=$form->field($model, 'address')->widget(\kalyabin\maplocation\SelectMapLocationWidget::className(), [
    'attributeLatitude' => 'system_address_lat',
    'attributeLongitude' => 'system_address_lng',
    'googleMapApiKey' => \Yii::$app->params['google_map_key'],
    'wrapperOptions' => ['class' => 'hide'],
]);?>
                        </div>
                        <div class="col-md-12">
                            <?=$form->field($model, 'address2')->textInput(['maxlength' => true])?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?=$form->field($model, 'postal_code')->textInput(['maxlength' => true])?>
                        </div>
                        <div class="col-md-4">
                            <?=$form->field($model, 'city')->textInput(['maxlength' => true])?>
                        </div>
                        <div class="col-md-4">
                            <?=$form->field($model, 'province')->textInput(['maxlength' => true])?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
echo $form->field($model, 'country_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map([1 => 'india'], 'id', 'name'),
    'options' => ['placeholder' => \Yii::t('app', 'Please Select'), 'multiple' => false],
]);
?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <div class="card-header pl-0 pb-0 mb-10">
                        <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Access Data');?></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?=$form->field($model, 'login_type')->dropDownList($model->enumItem($model, 'login_type'), ['prompt' => \Yii::t('app', 'Please select')])?>
                        </div>
                        <div class="col-md-3">
                            <?=$form->field($model, 'role')->dropDownList(\Yii::$app->userData->roles(), ['prompt' => \Yii::t('app', 'Please select')])?>
                        </div>
                        <div class="col-md-6">
                            <?=$form->field($model, 'email')->textInput(['maxlength' => true])?>
                        </div>
                    </div>

                    <?php if (!$model->id) {?>
                    <div class="row">
                        <div class="col-md-6">
                            <?=$form->field($model, 'password_hash')->textInput(['class' => 'password form-control'])?>
                        </div>
                        <div class="col-md-6 mt-7">
                            <?=Html::button(Yii::t('app', 'Generate Password'), ['class' => 'generate-code btn-block btn btn-primary']);?>
                        </div>
                    </div>
                    <?php }?>
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