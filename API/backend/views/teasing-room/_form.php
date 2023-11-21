<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TeasingRoom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teasing-room-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'media')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'thumb_media')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'caption')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'likes')->textInput() ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

    <?= $form->field($model, 'reason_for_disable')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
