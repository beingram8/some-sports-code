<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TokenPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Token Plan Form');?></h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'token')->textInput() ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'name')->textInput() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'price')->textInput() ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'reel_amount')->textInput() ?>
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
