<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SeasonMatch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="season-match-form">

    <?php $form = ActiveForm::begin();?>

    <?=$form->field($model, 'season')->textInput()?>

    <?=$form->field($model, 'league_id')->textInput()?>

    <?=$form->field($model, 'match_timestamp')->textInput()?>

    <?=$form->field($model, 'winner_team_id')->textInput()?>

    <?=$form->field($model, 'match_date')->textInput()?>

    <?=$form->field($model, 'team_home_id')->textInput()?>

    <?=$form->field($model, 'team_away_id')->textInput()?>

    <?=$form->field($model, 'match_ground')->textInput(['maxlength' => true])?>

    <?=$form->field($model, 'match_city')->textInput(['maxlength' => true])?>

    <?=$form->field($model, 'goal_of_home_team')->textInput()?>

    <?=$form->field($model, 'goal_of_away_team')->textInput()?>

    <?=$form->field($model, 'is_match_finished')->textInput()?>

    <?=$form->field($model, 'vote_closing_at')->textInput()?>

    <?=$form->field($model, 'api_match_id')->textInput()?>

    <?=$form->field($model, 'api_response')->textarea(['rows' => 6])?>

    <div class="form-group">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveForm::end();?>

</div>