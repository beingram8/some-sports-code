<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap\ActiveForm;

$this->title = \Yii::t('app', 'Setup');
?>
<div class="login-form login-signin">
    <div class="text-center mb-10 mb-lg-20">
        <h3 class="font-size-h1"><?=Yii::t('app', 'Setup Super Admin Account');?>
            <?=\Yii::$app->params['app_name'];?></h3>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'setup-form', 'options' => ['class' => 'form fv-plugins-bootstrap fv-plugins-framework']]);?>
    <?php if (Yii::$app->session->hasFlash('setup_account')) {?> <div
        class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
        <div class="alert-icon"><i class="fa fa-check"></i></div>
        <div class="alert-text"><?php echo Yii::$app->session->getFlash('setup_account'); ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <?php }?>
    <?php if (Yii::$app->session->hasFlash('setup_danger')) {?> <div
        class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
        <div class="alert-icon"><i class="fa fa-check"></i></div>
        <div class="alert-text"><?php echo Yii::$app->session->getFlash('setup_danger'); ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <?php }?>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'email')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-3">
            <?php echo $form->field($model, 'phone_code')
    ->widget(\kartik\select2\Select2::classname(), [
        'data' => \Yii::$app->employee->phoneCode(),
        'options' => ['placeholder' => \Yii::t('app', 'Please Select'), 'multiple' => false],
    ]);
?>
        </div>

        <div class="col-md-3">
            <?=$form->field($model, 'phone')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-12">
            <?=$form->field($model, 'password')->passwordInput(['maxlength' => true])?>
        </div>
        <div class="col-md-12">
            <?php
echo $form->field($model, 'timezone')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->general->getTimezoneList(),
    'options' => ['placeholder' => \Yii::t('app', 'Please Select'), 'multiple' => false],
]);
?>
        </div>
        <div class="col-md-12">
            <?=$form->field($model, 'address')->widget(\kalyabin\maplocation\SelectMapLocationWidget::className(), [
    'attributeLatitude' => 'system_address_lat',
    'attributeLongitude' => 'system_address_lng',
    'googleMapApiKey' => \Yii::$app->params['google_map_key'],
    'wrapperOptions' => ['class' => 'hide'],
]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'postal_code')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'city')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'province')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-6">
            <?php
echo $form->field($model, 'country_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\common\models\Country::find()->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => \Yii::t('app', 'Please Select'), 'multiple' => false],
]);
?>
        </div>
    </div>

    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">

        <button type="submit" class=" btn-block btn btn-primary font-weight-bold px-9 py-4 my-3">
            <?=Yii::t('app', 'Setup Account');?></button>
    </div>

</div>
<?php ActiveForm::end();?>
</div>