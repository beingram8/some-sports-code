<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserPointTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="card card-custom bg-white gutter-b">
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header pl-0 pb-0 mb-10">
                    <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'User Point Transaction Form');?></h3>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'type')->dropDownList(['1' => 'Match', '2' => 'Winning','3' => 'Buying', '4' => 'By Admin'], ['prompt' => 'Select Point type'])?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'transaction_type')->dropDownList(['1' => 'Credit', '0' => 'Debit'], ['prompt' => 'Select Transaction type'])?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'points')->textInput()->hint("Always add value in positive integer even if transaction type is debit"); ?>
                    </div>
                </div>

                <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>

                </div>
            </div>
        </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
    </div>
</div>
<?php ActiveForm::end();?>