<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = \Yii::t('app', 'Reset Password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-form login-signin">
    <div class="text-center mb-10 mb-lg-20">
        <h3 class="font-size-h1"><?=Yii::t('app', 'Reset Password');?></h3>
        <p class="text-muted font-weight-bold"><?=Yii::t('app', 'Please choose your new password');?></p>
    </div>
    <!--begin::Form-->
    <?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'options' => ['class' => 'form fv-plugins-bootstrap fv-plugins-framework']]);?>


    <?=$form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => \Yii::t('app', 'Password'),
    'class' => 'form-control form-control-solid h-auto py-5 px-6 is-invalid'])->label(false);?>

    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
        <a href="<?php echo Url::to(['site/forget-password'], $schema = true) ?>"
            class="text-dark-50 text-hover-primary my-3 mr-2"><?=Yii::t('app', 'Forget Password?');?></a>
        <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3">
            <?=Yii::t('app', 'Save');?></button>
    </div>

    <?php ActiveForm::end();?>
</div>