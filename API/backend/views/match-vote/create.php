<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserMatchVote */

$this->title = Yii::t('app', 'Create User Match Vote');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Match Votes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-match-vote-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
