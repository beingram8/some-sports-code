<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'View User: {name}', [
    'name' => $model->userData->username,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
            <?=DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'username',
            'value' => function ($model) {
                return $model->userData->username;
            },
        ],
        [
            'attribute' => 'photo',
            'format' => 'html',
            'value' => function ($model) {
                return Yii::$app->img->showImage($model->userData->photo);
            },
        ],
        [
            'attribute' => 'first_name',
            'value' => function ($model) {
                return $model->userData->first_name;
            },
        ],
        [
            'attribute' => 'lastname',
            'value' => function ($model) {
                return $model->userData->last_name;
            },
        ],
        'email',
        [
            'attribute' => 'is_social',
            'label' => 'Is Social Account',
            'format' => 'html',
            'value' => function ($model) {
                if ($model->is_social == 1) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Yes') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'No') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'status',
            'filter' => ['10' => 'Active', '9' => 'Disabled', '0' => 'Deleted'],
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->status == 10) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Enabled') . '</span>';
                } else if ($model->status == 9) {
                    return '<span class="label label-warning label-pill label-inline mr-2">' . Yii::t('app', 'Disabled') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'Deleted') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'birth_date',
            'value' => function ($model) {
                return Yii::$app->general->format_date(strtotime($model->userData->birth_date));
            },
        ],
        [
            'attribute' => 'Gender',
            'value' => function ($model) {
                if($model->userData->gender == 1){
                    return 'Male';
                } else if($model->userData->gender == 2){
                    return 'Female';
                }else {
                    return 'Other';
                }
            },
        ],
        [
            'attribute' => 'city',
            'value' => function ($model) {
                return $model->userData->city_id == '' ? '-' : $model->userData->city->name;
            },
        ],
        [
            'attribute' => 'education',
            'value' => function ($model) {
                return $model->userData->education_id == '' ? '-' : $model->userData->education->name;
            },
        ],
        [
            'attribute' => 'job',
            'value' => function ($model) {
                return $model->userData->job_id == '' ? '-' : $model->userData->job->name;
            },
        ],
        [
            'attribute' => 'point',
            'value' => function ($model) {
                return $model->userData->point;
            },
        ],
        [
            'attribute' => 'token',
            'value' => function ($model) {
                return $model->userData->token;
            },
        ],
        // [
        //     'attribute' => 'fiscal_code',
        //     'value' => function ($model) {
        //         return $model->userData->fiscal_code == '' ? '-' : $model->userData->fiscal_code;
        //     },
        // ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Yii::$app->general->format_date($model->created_at);
            },
        ],
    ],
])?>
            </div>
        </div>
    </div>
</div>