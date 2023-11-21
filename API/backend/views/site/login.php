<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'Login');
?>
<div class="login-form login-signin">
    <div class="text-center mb-10 mb-lg-20">
        <h3 class="font-size-h1"><?=Yii::t('app', 'Welcome to ');?> <?=\Yii::$app->params['app_name'];?></h3>
        <p class="text-muted font-weight-bold"><?=Yii::t('app', 'Enter your details to log in to your account');?></p>
    </div>
    <?php if (!empty($_GET['username']) && !empty($_GET['password'])) {?>
    <?php $form = ActiveForm::begin(['id' => 'auto-login-form', 'options' => ['class' => 'form fv-plugins-bootstrap fv-plugins-framework']]);?>
    <?=$form->field($model, 'username')->hiddenInput(['value' => $_GET['username']])->label(false);?>
    <?=$form->field($model, 'password')->hiddenInput(['value' => $_GET['password']])->label(false);?>
    <?php ActiveForm::end();?>
    <script>
    $(document).ready(function() {
        setTimeout(() => {
            $('#auto-login-form').submit();
        }, 1000);
    })
    </script>
    <?php }?>
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form fv-plugins-bootstrap fv-plugins-framework']]);?>
    <?php if (Yii::$app->session->hasFlash('success_reset')) {?> <div
        class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
        <div class="alert-icon"><i class="fa fa-check"></i></div>
        <div class="alert-text"><?php echo Yii::$app->session->getFlash('success_reset'); ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
    <?php }?>

    <?=$form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => \Yii::t('app', 'Username'),
    'class' => 'form-control form-control-solid h-auto py-5 px-6 is-invalid'])->label(false)?>
    <?=$form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => \Yii::t('app', 'Password'),
    'class' => 'form-control form-control-solid h-auto py-5 px-6 is-invalid'])->label(false);?>

    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
        <a href="<?php echo Url::to(['site/forget-password'], $schema = true) ?>"
            class="text-dark-50 text-hover-primary my-3 mr-2"><?=Yii::t('app', 'Forget Password?');?></a>
        <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3">
            <?=Yii::t('app', 'Sign In');?></button>
    </div>

    <?php ActiveForm::end();?>
</div>