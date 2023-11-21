<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['method' => 'POST']);?>
<div class="row">
    <div class="col-md-4">
        <?=$form->field($model, 'season')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->season->getSeasons(),
    'language' => 'en',
    'name' => 'season',
    'options' => ['placeholder' => 'Select season'],
]);?>
    </div>
    <div class="col-md-6">
        <?=$form->field($model, 'league_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->league->getLeagues(true),
    'name' => 'league_id',
    'options' => ['placeholder' => 'Select League'],
]);?>
    </div>
    <div class="col-md-2 mt-7">
        <?=Html::submitButton('Fetch Matches', ['class' => 'btn-block btn btn-success'])?>
    </div>
</div>
<?php ActiveForm::end();?>