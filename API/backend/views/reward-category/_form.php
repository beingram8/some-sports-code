<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RewardCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin();?>
    <div class="card card-custom bg-white gutter-b">
        <div class="card-body pt-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header pl-0 pb-0 mb-10">
                        <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Category Form');?></h3>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
                        </div>
                        <div class="col-md-6">
                            <?=$form->field($model, 'order_no')->textInput(['maxlength' => true])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="card-footer">
        <button   type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>
