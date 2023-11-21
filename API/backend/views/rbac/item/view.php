<?php

use yii2mod\rbac\RbacAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

RbacAsset::register($this);

/* @var $this yii\web\View */
/* @var $model \yii2mod\rbac\models\AuthItemModel */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Item'] . ' : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column breadcrumbs">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">
                    <?=Html::encode($this->title)?>
                </h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <?php echo Breadcrumbs::widget([
    'tag' => 'div',
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'itemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
    'options' => ['class' => 'd-flex m-left-8 align-items-center font-weight-bold my-2',
        'style' => "color: #fff;"],
    'activeItemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
]);
?>


                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <?php echo Html::a(Yii::t('yii2mod.rbac', '<< Back'), ['index'], ['class' => 'mr-5 btn btn-white']); ?>

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <div class="auth-item-view">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'description:ntext',
        'ruleName',
        'data:ntext',
    ],
]); ?>
                        </div>
                    </div>
                    <?php echo $this->render('../_dualListBox', [
    'opts' => Json::htmlEncode([
        'items' => $model->getItems(),
    ]),
    'assignUrl' => ['assign', 'id' => $model->name],
    'removeUrl' => ['remove', 'id' => $model->name],
]); ?>
                </div>