<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */

$this->title =
Yii::t('app', 'Sync to Master');
$this->params['breadcrumbs'][] = $this->title;
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
    </div>
</div>

<!--begin::Container-->
<?php if (Yii::$app->session->hasFlash('success_sync')) {?>
<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
    <div class="alert-icon"><i class="fa fa-check"></i></div>
    <div class="alert-text"><?php echo Yii::$app->session->getFlash('success_sync'); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php }?>
<?php if (Yii::$app->session->hasFlash('failed_sync')) {?>
<div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
    <div class="alert-icon"><i class="fa fa-check"></i></div>
    <div class="alert-text"><?php echo Yii::$app->session->getFlash('failed_sync'); ?></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php }?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-custom  example example-compact">

            <div class="card-body">
                <?php $form = ActiveForm::begin();?>
                <div class="row">
                    <div class="col-md-9">
                        <?=$form->field($model, 'access_token')
->textArea(['style' => 'height:90px'])->label(\Yii::t('app', 'API Sync Token'))
->hint(\Yii::t('app', 'Please add this token to master panel. This token is use for the backend login as well as syncing process. So please take care of it. Do not update once you setup with master.'));
?>
                    </div>
                    <div class="col-md-3 mt-7">
                        <?php if ($model->id) {?>
                        <button type="submit" name="updateToken" value="yes"
                            data-confirm="<?=\Yii::t('app', 'Are you sure to update token ? Once you update it you have to add new token to master for syncing..');?>"
                            class="btn btn-warning btn-block mt-3"><?=\Yii::t('app', 'Update Sync Token');?></button>
                        <?php } else {?>
                        <button type="submit"
                            class="btn-block btn btn-success"><?=\Yii::t('app', 'Generate Token');?></button>
                        <?php }?>

                    </div>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>