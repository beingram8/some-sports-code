<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'Forget Password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-form login-signin">

    <div class="text-center mb-10 mb-lg-20">
        <h3 class="font-size-h1"><?=Yii::t('app', 'Forget your password ?');?></h3>
        <p class="text-muted font-weight-bold"><?=Yii::t('app', 'Enter the email associated with your account.
        We will send you an email with instructions');?></p>
    </div>
    <!--begin::Form-->
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form fv-plugins-bootstrap fv-plugins-framework']]);?>
    <?php if (Yii::$app->session->hasFlash('success_reset_ps')) {?>
    <div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
        <div class="alert-icon"><i class="fa fa-check"></i></div>
        <div class="alert-text"><?php echo Yii::$app->session->getFlash('success_reset_ps'); ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <?php }?>
    <?php if (Yii::$app->session->hasFlash('error_reset_ps')) {?>
    <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
        <div class="alert-icon"><i class="fa fa-times"></i></div>
        <div class="alert-text"><?php echo Yii::$app->session->getFlash('error_reset_ps'); ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <?php }?>
    <?=$form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => \Yii::t('app', 'Email'),
    'class' => 'form-control form-control-solid h-auto py-5 px-6 is-invalid'])->label(false)?>

    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
        <a href="<?php echo Url::to(['/site/index'], $schema = true) ?>"
            class="text-dark-50 text-hover-primary my-3 mr-2"><?=Yii::t('app', 'Lets go back to login');?></a>
        <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3">
            <?=Yii::t('app', 'Send');?>
        </button>
    </div>

    <?php ActiveForm::end();?>
</div>