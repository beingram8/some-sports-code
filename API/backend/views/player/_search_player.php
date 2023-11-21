<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AppointmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appointment-search">
    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]);?>
    <div class="row">
        <div class="col-md-4">
            <?php
                echo $form->field($model, 'season')->widget(\kartik\select2\Select2::classname(), [
                    'data' =>\Yii::$app->season->getSeasons(),
                    'options' => ['placeholder' => \Yii::t('app','Please Select'), 'multiple' => false],
                ]);
            ?>
        </div>
        <div class="col-md-4">
            <?php
                echo $form->field($model, 'league')->widget(\kartik\select2\Select2::classname(), [
                    'data' =>\Yii::$app->league->getLeagues(),
                    'options' => ['placeholder' => \Yii::t('app','Please Select'), 'multiple' => false],
                ]);
            ?>
        </div>
        <div class="col-md-2">
            <?php
                echo $form->field($model, 'match_day')->widget(\kartik\select2\Select2::classname(), [
                    'data' =>\Yii::$app->general->matchDayArray(),
                    'options' => ['placeholder' => \Yii::t('app','Please Select'), 'multiple' => false],
                ]);
            ?>
        </div>
        <div class="col-md-2 mt-8">
            <?=Html::submitButton(\Yii::t('app','Search'), ['class' => ' btn btn-primary'])?>
            <a data-pjax=0 href="<?=Url::to(['player/index'], $schema = true)?>"
                class="btn btn-secondary font-weight-bold py-3 px-6"><?=\Yii::t('app', 'Reset')?></a>
        </div>
    </div>
    
</div>
<?php ActiveForm::end();?>