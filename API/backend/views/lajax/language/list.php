<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
use yii\grid\GridView;
use yii\helpers\Html;
use lajax\translatemanager\models\Language;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel lajax\translatemanager\models\searches\LanguageSearch */

$this->title = Yii::t('language', 'List of languages');
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
            <?=Html::a(Yii::t('app', 'Scan'),
    ['scan'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6 mr-2'])?>
            <?=Html::a(Yii::t('app', 'Import'),
    ['import'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6 mr-2'])?>
            <?=Html::a(Yii::t('app', 'Export'),
    ['export'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6 mr-2'])?>
            <?=Html::a(Yii::t('app', 'Add New'),
    ['create'], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6 mr-2'])?>

        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <div id="languages">

                    <?php
                        Pjax::begin([
                            'id' => 'languages',
                        ]);
                        echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'language_id',
                                'name_ascii',
                                [
                                    'format' => 'raw',
                                    'filter' => Language::getStatusNames(),
                                    'attribute' => 'status',
                                    'filterInputOptions' => ['class' => 'form-control', 'id' => 'status'],
                                    'label' => Yii::t('language', 'Status'),
                                    'content' => function ($language) {
                                        return Html::activeDropDownList($language, 'status', Language::getStatusNames(), ['class' => 'status', 'id' => $language->language_id, 'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/change-status')]);
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'attribute' => Yii::t('language', 'Statistic'),
                                    'content' => function ($language) {
                                        return '<span class="statistic"><span style="width:' . $language->gridStatistic . '%"></span><i>' . $language->gridStatistic . '%</i></span>';
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{translate} {update}  {delete}',
                                    'buttons' => [
                                        'translate' => function ($url, $model, $key) {
                                            return Html::a('Translate', ['language/translate', 'language_id' => $model->language_id], [
                                                'title' => Yii::t('language', 'Translate'),
                                                'data-pjax' => '0',
                                                'class'=>'btn btn-primary btn-sm'
                                            ]);
                                        },
                                    ],
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