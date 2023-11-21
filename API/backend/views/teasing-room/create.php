<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TeasingRoom */

$this->title = Yii::t('app', 'Create Teasing Room');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teasing Rooms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teasing-room-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
