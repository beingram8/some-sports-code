<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =
Yii::t('app', 'Season Teams');
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
            <div class="dropdown dropdown-inline ml-2 show" data-toggle="tooltip" title="" data-placement="top"
                data-original-title="Fetch Leagues">
                <a href="#" class="btn btn-white font-weight-bold py-3 px-6" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">Fetch Teams</a>
                <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                    <ul class="navi navi-hover py-5">
                        <?php foreach (\Yii::$app->season->getSeasons() as $season => $title) {?>
                        <li class="navi-item">
                            <a href="<?=Url::to(['season-team/fetch', 'season' => $season], $schema = true)?>"
                                class="navi-link">
                                <span class="navi-text"><?=$title;?></span>
                            </a>
                        </li>
                        <?php }?>
                    </ul>
                </div>
            </div>

        </div>
        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <?php echo $this->render('_fetch_team', ['model' => new \backend\models\FetchTeamForm]); ?>
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
            'headerOptions' => ['style' => 'width:10%'],
        ],
        [
            'attribute' => 'api_team_id',
            'headerOptions' => ['style' => 'width:10%'],
        ],
        [
            'attribute' => 'season',
            'headerOptions' => ['style' => 'width:10%'],
            'filter' => \Yii::$app->season->getSeasons(),
        ],
        [
            'attribute' => 'name',
            'headerOptions' => ['style' => 'width:15%'],
            'format' => 'html',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle symbol-50 mr-3">
                        <img alt="Pic" src="' . $model->logo . '">
                    </div>
                    <div class="d-flex flex-column">
                        <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . $model->name . '</a>
                    </div>
                </div>
            </div>';

            },
        ],
        [
            'attribute' => 'is_active',
            'filter' => ['1' => 'Active', '0' => 'Disable'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['team/update-active'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_active', $model->is_active, ['1' => 'Active', '0' => 'Disable'], ['prompt' => '', 'class' => 'is-active-ajax-dropdown form-control', 'data-id' => $model->id, 'data-option' => $model->is_active]) . '
                        </form>';
            },
        ],
        [
            'attribute' => 'is_main_team',
            'filter' => ['1' => 'Yes', '0' => 'No'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['team/update-is-main'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_main_team', $model->is_main_team, ['1' => 'Yes', '0' => 'No'], ['prompt' => '', 'class' => 'is_main_team-ajax-dropdown form-control', 'data-id' => $model->id, 'data-is_main_team' => $model->is_main_team]) . '
                        </form>';
            },
        ],
        [
            'attribute' => 'is_national_team',
            'filter' => ['1' => 'Yes', '0' => 'No'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['team/update-is-national'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_national_team', $model->is_national_team, ['1' => 'Yes', '0' => 'No'], ['prompt' => '', 'class' => 'is-national-team-ajax-dropdown form-control', 'data-id' => $model->id, 'data-is-national-team' => $model->is_national_team]) . '
                        </form>';
            },
        ],
        // [
        //     'attribute' => 'price_for_super_fab_package',
        //     'format' => 'html',
        //     'value' => function ($model) {
        //         return \Yii::$app->formatter->asCurrency($model->price_for_super_fab_package);
        //     },
        // ],
        // ['class' => 'yii\grid\ActionColumn', 'template' => '{u}'],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>