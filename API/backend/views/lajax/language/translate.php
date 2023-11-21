<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
use lajax\translatemanager\helpers\Language;
use lajax\translatemanager\models\Language as Lang;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $language_id string */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel lajax\translatemanager\models\searches\LanguageSourceSearch */
/* @var $searchEmptyCommand string */

$this->title = Yii::t('language', 'Translation into {language_id}', ['language_id' => $language_id]);
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
            </div>

        </div>
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', '<< Back'),
    ['list'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>

        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <?=Html::hiddenInput('language_id', $language_id, ['id' => 'language_id', 'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/save')]);?>
                <div id="translates" class="<?=$language_id?>">
                    <?php
Pjax::begin([
    'id' => 'translates',
]);
$form = ActiveForm::begin([
    'method' => 'get',
    'id' => 'search-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
]);
echo $form->field($searchModel, 'source')->dropDownList(['' => Yii::t('language', 'Original')] + Lang::getLanguageNames(true))->label(Yii::t('language', 'Source language'));
ActiveForm::end();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'format' => 'raw',
            'filter' => Language::getCategories(),
            'attribute' => 'category',
            'filterInputOptions' => ['class' => 'form-control', 'id' => 'category'],
        ],
        [
            'format' => 'raw',
            'attribute' => 'message',
            'filterInputOptions' => ['class' => 'form-control', 'id' => 'message'],
            'label' => Yii::t('language', 'Source'),
            'content' => function ($data) {
                return Html::textarea('LanguageSource[' . $data->id . ']', $data->source, ['class' => 'form-control source', 'readonly' => 'readonly']);
            },
        ],
        [
            'format' => 'raw',
            'attribute' => 'translation',
            'filterInputOptions' => [
                'class' => 'form-control',
                'id' => 'translation',
                'placeholder' => $searchEmptyCommand ? Yii::t('language', 'Enter {command} to search for empty translations.', ['command' => $searchEmptyCommand]) : '',
            ],
            'label' => Yii::t('language', 'Translation'),
            'content' => function ($data) {
                return Html::textarea('LanguageTranslate[' . $data->id . ']', $data->translation, ['class' => 'form-control translation', 'data-id' => $data->id, 'tabindex' => $data->id]);
            },
        ],
        [
            'format' => 'raw',
            'label' => Yii::t('language', 'Action'),
            'content' => function ($data) {
                return Html::button(Yii::t('language', 'Save'), ['type' => 'button', 'data-id' => $data->id, 'class' => 'btn btn-lg btn-success']);
            },
        ],
    ],
]);
Pjax::end();
?>

                </div>
            </div>
        </div>
    </div>
</div>