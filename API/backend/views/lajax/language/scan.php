<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use lajax\translatemanager\helpers\Language;
use lajax\translatemanager\models\Language as Lang;

$this->title = Yii::t('language', 'Scanning project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
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
            <?=Html::a(Yii::t('app', '<< Back'),
    ['list'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6 mr-2'])?>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <div id="languages">
                    <div id="w2-info" class="alert-primary alert fade in">
                        <?= Yii::t('language', '{n, plural, =0{No new entries} =1{One new entry} other{# new entries}} were added!', ['n' => $newDataProvider->totalCount]) ?>
                    </div>

                    <?= $this->render('__scanNew', [
    'newDataProvider' => $newDataProvider,
]) ?>

                    <div id="w2-danger" class="alert-danger alert fade in">
                        <?= Yii::t('language', '{n, plural, =0{No entries} =1{One entry} other{# entries}} remove!', ['n' => $oldDataProvider->totalCount]) ?>
                    </div>

                    <?= $this->render('__scanOld', [
    'oldDataProvider' => $oldDataProvider,
]) ?>
                </div>
            </div>
        </div>
    </div>
</div>