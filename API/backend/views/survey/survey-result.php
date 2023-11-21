<?php

use common\models\SurveyQuestion;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Result for ' . $sponsored_by;
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

<?=ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "<div class='row p-4'>{items}</div>{pager}",
    'itemOptions' => ['tag' => null],
    'itemView' => function ($model, $key, $index, $widget) {

        $html = "";
        if ($model->option_type == 1) {
            $questionOption = $model->surveyQuestionOptions;
            foreach ($questionOption as $k => $option) {

                $html .= '  <div class="symbol symbol-50 mr-20">
                <img alt="Pic" src="' . $option->option_as_img . '"/>
                <h6 class="card-label mt-5">Option-' . ($k + 1) . '</h6>
            </div>';
            }
        }
        return '<div class="col-lg-6 mt-6">
                    <div class="card card-custom card-stretch">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">' . $model->question . '</h3>
                            </div>
                        </div>
                    <div class="card-body">


                    ' . Highcharts::widget([
            'options' => [
                'chart' => [
                    'type' => 'column',
                ],
                'title' => [
                    'text' => 'Voted options',
                ],
                'accessibility' => [
                    'announceNewData' => [
                        'enabled' => true,
                    ],
                ],
                'xAxis' => [
                    'type' => 'category',
                ],
                'yAxis' => [
                    'title' => [
                        'text' => 'Total number of votes',
                    ],
                ],
                'legend' => [
                    'enabled' => false,
                ],
                'plotOptions' => [
                    'series' => [
                        'borderWidth' => 0,
                    ],
                ],
                'credits' => ['enabled' => false],
                'series' => [
                    [
                        'name' => "Option voting chart",
                        'colorByPoint' => true,
                        'data' => SurveyQuestion::graphData($model->id),
                    ],
                ],
            ],
        ]) . ' <div class="d-flex align-items-center mt-10 ml-25">' . $html . '</div>
                    </div>
                </div>
            </div>';
    },
]);
?>