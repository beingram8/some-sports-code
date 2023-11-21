<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' =>
    Yii::t('app', 'Users'),
    'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
            <?=Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' =>
    'btn
            btn-primary', ])?>
            <?=Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        'method' => 'post',
    ],
])?>

        </div>
        <!--end::Toolbar-->
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
        'name',
        'surname',
        'address_type',
        'address',
        'address2',
        'postal_code',
        'city',
        'province',
        'country_id',
        'phone',
        'email:email',
        'password_hash',
        'profile_pic',
        'agent_tbl',
        'available',
        'lang_code',
        'role',
        'login_type',
        'token_DNI',
        'token_certificate',
        'token_Token',
        'password_reset_token',
        'credit',
        'status',
        'verification_token',
        'auth_key',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ],
])?>
            </div>
        </div>
    </div>
</div>