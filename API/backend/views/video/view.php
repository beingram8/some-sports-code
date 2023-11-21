<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model common\models\Video */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'List'),
    ['index'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
        </div>
    </div>
</div>
<div class="container">
    <div class="card card-custom card-stretch">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-title mb-3"><?=Yii::t('app', 'Product Details');?></h3>
            </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    [
                        'attribute' => 'video_url',
                        'format' => 'raw',
                        'filter' => false,
                        'value' => function ($model) {
                            return '<video width="150" height="150" controls>
                                    <source src="'.$model->video_url.'" type="video/mp4">
                                    </video>';
                        },
                    ],
                    'description:ntext',
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return Yii::$app->general->format_date($model->created_at);
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
