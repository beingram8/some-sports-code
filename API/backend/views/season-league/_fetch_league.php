<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['method' => 'POST', 'action' => ['/fetch/fetch-league']]);?>
<div class="row">
    <div class="col-md-3">
        <?=$form->field($model, 'season')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->season->getSeasons(),
    'language' => 'en',
    'name' => 'season',
    'options' => ['placeholder' => 'Select season'],
]);?>
    </div>
    <div class="col-md-3">
        <?=$form->field($model, 'country')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->general->country(),
    'language' => 'en',
    'name' => 'country',
    'options' => ['placeholder' => 'Select country'],
]);?>
    </div>
    <div class="col-md-4">
        <?php
echo $form->field($model, 'leagues')->widget(\kartik\depdrop\DepDrop::classname(), [
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
    'options' => [
        'multiple' => true,
        'placeholder' => 'Select'
    ],
    'name' => 'leagues',

    'pluginOptions' => [
        'depends' => ['fetchleagueform-season', 'fetchleagueform-country'],
        // 'loading' => true,
        // 'loadingText' => 'Fetching Data...',
        'allowClear' => true,
        'url' => \yii\helpers\Url::to(['/fetch/get-leagues-by-season-and-country']),
    ],
]);
?>
    </div>
    <div class="col-md-2 mt-7">
        <?=Html::submitButton('Fetch League', ['class' => 'btn-block btn btn-success'])?>
    </div>
</div>
<?php ActiveForm::end();?>