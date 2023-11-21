<?php

use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin();?>
<!--begin::Container-->
<?php if (Yii::$app->session->hasFlash('success_password')) {?>
<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
    <div class="alert-icon"><i class="fa fa-check"></i></div>
    <div class="alert-text"><?php echo Yii::$app->session->getFlash('success_password'); ?></div>
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
                <h3 class="card-title"><?=Yii::t('app', 'Change Password');?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'password')->passwordInput(['maxlength' => true])?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'cpassword')->passwordInput(['maxlength' => true])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success mr-2"><?=\Yii::t('app', 'Change Password');?></button>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>