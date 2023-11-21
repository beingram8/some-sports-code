<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
/* @var $form yii\widgets\ActiveForm */
$this->title = \Yii::t('app', 'Payment Methods');
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
    </div>
</div>
<?php Pjax::begin();?>
<div class="d-flex flex-column-fluid">
    <div class="container">

        <div class="card card-custom card-shadowless rounded-top-0">
            <div class="card-body">
                <div class="row col-md-12">
                    <?php if (Yii::$app->session->hasFlash('method_success')) {?> <div
                        class="col-md-12 alert alert-custom alert-notice alert-light-success fade show" role="alert">
                        <div class="alert-icon"><i class="fa fa-check"></i></div>
                        <div class="alert-text"><?php echo Yii::$app->session->getFlash('method_success'); ?></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class="row">



                    <?php

if ($methods) {
    foreach ($methods as $method) {
        $methodParams = !empty($method['json']) ? json_decode($method['json'], true) : [];
        if ($methodParams) {
            $methodParamsAttributes = array_keys($methodParams);
            $dynamicModel = new \yii\base\DynamicModel($methodParamsAttributes);
            $dynamicModel->addRule($methodParamsAttributes, 'string');
            $dynamicModel->addRule($methodParamsAttributes, 'required');
            ?>

                    <div class="col-md-6">
                        <?php $form = ActiveForm::begin();?>


                        <div class="accordion accordion-solid accordion-toggle-plus">
                            <div class="card">
                                <label class="card-header" for="<?=$method['id']?>">
                                    <div class="method card-title collapsed" data-toggle="collapse"
                                        data-target="#collapseOne<?=$method['id']?>">

                                        <?=$method['method_name']?>
                                    </div>
                                </label>
                                <div id="collapseOne<?=$method['id']?>" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <?php if ($methodParams) {
                foreach ($methodParams as $attribute => $value) {
                    ?>
                                            <div class="col-md-6 form-group">
                                                <?=$form->field($dynamicModel, $attribute)->textInput(['value' => $value]);
                    ?>

                                            </div>
                                            <?php }?>
                                            <?php
}?>
                                        </div>
                                        <div class="row">
                                            <div class="col text-left">

                                                <button type="submit" name="method_name"
                                                    value="<?=$method['method_name']?>"
                                                    class="btn btn-primary btn-block"><?=\Yii::t('app', 'Save');?>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end();?>
                    </div>

                    <?php
}
    }
}
?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>