<?php

use lajax\translatemanager\models\ExportForm;
use lajax\translatemanager\models\Language;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

/* @var $this yii\web\View */
/* @var $model ExportForm */

$this->title = Yii::t('language', 'Export');
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
                    <?=yii\helpers\Html::encode($this->title)?>
                </h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <?php echo yii\widgets\Breadcrumbs::widget([
    'tag' => 'div',
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'itemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
    'options' => ['class' => 'd-flex m-left-8 align-items-center font-weight-bold my-2',
        'style' => "color: #fff;"],
    'activeItemTemplate' => '<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>{link}',
]);
?>

                <?php //                 echo $this->render('_search',['model' => $searchModel]); ?>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <div class="d-flex align-items-center">
            <?=\yii\helpers\Html::a(Yii::t('app', '<< Back'),
    ['list'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6 mr-2'])?>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'exportLanguages')
                ->listBox(ArrayHelper::map(Language::find()->all(), 'language_id', 'name_ascii'), [
    'multiple' => true,
    'size' => 20,
]) ?>

                <?= $form->field($model, 'format')->radioList([
    Response::FORMAT_JSON => Response::FORMAT_JSON,
    Response::FORMAT_XML => Response::FORMAT_XML,
]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('language', 'Export'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>