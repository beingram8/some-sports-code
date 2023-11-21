<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonLeagueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =
Yii::t('app', 'Season Leagues');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin();?>
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

                <?php //                 echo $this->render('_search',['model' => $searchModel]); ?>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <?php echo $this->render('_fetch_league', ['model' => new \backend\models\FetchLeagueForm]); ?>
            </div>
        </div>
        <br>
        <div class="card card-custom">
            <div class="card-body">



                <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:7%'],
        ],
        [
            'attribute' => 'api_league_id',
            'headerOptions' => ['style' => 'width:10%'],
        ],
        [
            'attribute' => 'season',
            'headerOptions' => ['style' => 'width:10%'],

            'filter' => \Yii::$app->season->getSeasons(),
        ],
        [
            'attribute' => 'country',
            'headerOptions' => ['style' => 'width:10%'],
            'filter' => \Yii::$app->general->country(),
            'value' => function ($model) {
                return \Yii::$app->general->getValueFromKey($model->country);
            },
        ],

        [
            'attribute' => 'name',
            'headerOptions' => ['style' => 'width:20%'],
            'format' => 'html',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center mr-2">
                    <div class="symbol symbol-40 symbol-light mr-3 flex-shrink-0">
                        <div class="symbol-label">
                            <img src="' . $model->logo . '" alt="" class="h-50">
                        </div>
                    </div>
                    <div>
                        <a href="#" class="font-size-sm text-dark-75 text-hover-primary font-weight-bolder">' . $model->name . '</a>

                    </div>
                </div>';
            },
        ],
        [
            'attribute' => 'is_active',
            'headerOptions' => ['style' => 'width:10%'],

            'filter' => \Yii::$app->general->tinyForActive(),
            'format' => 'html',
            'value' => function ($model) {

                return \Yii::$app->general->tinyForActive($model->is_active, true);;
            },
        ],
        [
            'attribute' => 'is_main',
            'headerOptions' => ['style' => 'width:10%'],
            'filter' => ['1' => 'Yes', '0' => 'No'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['season-league/update-is-main'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_main', $model->is_main, ['1' => 'Yes', '0' => 'No'], ['prompt' => '', 'class' => 'is_main-ajax-dropdown form-control', 'data-id' => $model->id, 'data-is_main' => $model->is_main]) . '
                        </form>';
            },
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>