<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['method' => 'POST', 'action' => ['/fetch/fetch-teams']]);?>
<div class="row">
    <div class="col-md-2">
        <?=$form->field($model, 'season')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->season->getSeasons(),
    'language' => 'en',
    'name' => 'season',
    'options' => ['placeholder' => 'Select season'],
]);?>
    </div>
    <div class="col-md-3">
        <?=$form->field($model, 'league_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->league->getLeagues(false),
    'name' => 'league_id',
    'options' => ['placeholder' => 'Select League'],
]);?>
    </div>
    <div class="col-md-2">
        <?=$form->field($model, 'team_for_which_country_id')->widget(\kartik\select2\Select2::classname(), [
    'data' => \Yii::$app->general->country(),
    'language' => 'en',
    'name' => 'country',
    'options' => ['placeholder' => "Team's Country"],
])->label("Team's Country");?>
    </div>
    <div class="col-md-3">
        <?php
echo $form->field($model, 'teams')->widget(\kartik\depdrop\DepDrop::classname(), [
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
    'options' => [
        'multiple' => true,
        'placeholder' => 'Select'
    ],
    'pluginOptions' => [
        'depends' => ['fetchteamform-season', 'fetchteamform-league_id', 'fetchteamform-team_for_which_country_id'],
        //'loading' => true,
        //'loadingText' => 'Fetching Data...',
        'allowClear' => true,
        'url' => \yii\helpers\Url::to(['/fetch/get-teams-by-season-league-country']),
    ],
]);
?>
    </div>
    <div class="col-md-2 mt-7">
        <?=Html::submitButton('Fetch Team', ['class' => 'btn-block btn btn-success'])?>
    </div>
</div>
<?php ActiveForm::end();?>