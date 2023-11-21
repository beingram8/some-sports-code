<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Quiz */

$this->title = 'Reward Code for '.$name.'';
$this->params['breadcrumbs'][] = ['label' => 'Reward', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="d-flex flex-column breadcrumbs">
                <h2 class="text-white font-weight-bold my-2 mr-5">
                    <?=Html::encode($this->title)?>
                </h2>
                <?php echo Breadcrumbs::widget([
    'tag' => 'div',
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'itemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
    'options' => ['class' => 'd-flex m-left-8 align-items-center font-weight-bold my-2',
        'style' => "color: #fff;"],
    'activeItemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
]);
?>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'List'),
    ['index'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'reward_id',
                            'label' => 'Reward Product',
                            'value' => function ($model) {
                                return Yii::$app->reward->getRewardName($model->reward_id);;
                            },
                        ],
                        'reward_code',
                        [
                            'attribute' => 'user_id',
                            'label' => 'User name',
                            'value' => function ($model) {
                                return Yii::$app->userData->formatName($model->user_id);;
                            },
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value' => function ($model) {
                                return !empty($model->updated_at) ? Yii::$app->general->format_date($model->updated_at) : '';
                            },
                        ],
                        
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>