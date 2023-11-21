<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonMatchWinnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quiz Winners';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin();?>
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
        <?php if(!empty($searchModel->quiz_id)) { ?>
        <div class="card-toolbar px-3">
            <?=\yii\helpers\Html::a(Yii::t('app', '<< Back'),
                ['quiz/index'], ['data-pjax' => '0', 'class' => 'btn btn-primary font-weight-bold py-3 px-6'])?>
        </div>
        <?php } ?>
    </div>

</div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <?php echo Yii::$app->general->getFlash(); ?>
            <div class="card-body">
                <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'quiz_id',
                    [
                        'attribute' => 'quiz',
                        'header' => 'Quiz Title',
                        'value' => function ($model) {
                            return $model->quiz->quiz_title;
                        }
                    ],
                    [
                        'attribute' => 'user',
                        'format' => 'html',
                        'header' => 'User',
                        'value' => function ($model) {
                            return '<div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50 mr-3">
                                    <img alt="Pic" src="'. Yii::$app->userData->photo($model->winnerUser->user_id).'"></div>
                                <div class="d-flex flex-column">
                                    <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">'.$model->winnerUser->first_name.' '.$model->winnerUser->last_name.'</a>
                                    <span class="text-muted font-weight-bold font-size-sm">@'.$model->winnerUser->username.'</span>
                                </div>
                            </div>
                        </div>';
                        },
                    ],

                ],
            ]); ?>

            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>