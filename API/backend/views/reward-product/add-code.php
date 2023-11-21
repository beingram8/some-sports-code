<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app', 'Add Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reward Code'), 'url' => ['index']];
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
            <div class="">
            <?php $importForm = new \app\models\ImportForm(); 
            $form = \yii\widgets\ActiveForm::begin(['action' => ['reward-product/import','id' => $id], 'options' => ['role' => 'form', 'enctype' => 'multipart/form-data']]);?>

            <div class="card card-custom bg-white gutter-b">
                <div class="card-body pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header pl-0 pb-0 mb-10">
                                <h3 class="card-title m-0 mb-3"><?=Yii::t('app', 'Import Form');?></h3>
                            </div>
                            <?php  ?>
                            <?= $form->field($importForm, 'file')->fileInput()->hint('<a href="' . Url::to(['reward-product/download']) . '">Download</a> sample csv file for Reward Code');?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2"><?=\Yii::t('app', 'Submit');?></button>
                    </div>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>