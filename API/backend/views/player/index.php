<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonTeamPlayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =
Yii::t('app', 'Season Match Players');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin();?>
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
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <?php echo $this->render('_search_player', ['model' => $searchModel]); ?>
                    </div>
                </div>
                <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:3%'],
        ],
        [
            'label' => 'Team',
            'format' => 'raw',
            'attribute' => 'team_id',
            'headerOptions' => ['style' => 'width:3%'],
            'filter' => Yii::$app->team->getTeams(),
            'value' => function ($model) {
                if ($model->team_id && isset($model->team->logo)) {
                    return '<div class="flex text-center"><div>' . Yii::$app->img->showImage($model->team->logo) . '</div><div>' . $model->team->name . '</div></div>';
                } else {
                    return '-';
                }
            },
        ],
        [
            'attribute' => 'player',
            'format' => 'raw',
            'header' => 'Player Info',
            'headerOptions' => ['style' => 'width:7%'],
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle symbol-50 mr-3">
                        <img alt="Pic" src="' . $model->player->photo . '">
                    </div>
                    <div class="d-flex flex-column">
                        <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . $model->player->name . '</a>
                        <span class="text-muted font-weight-bold font-size-sm">' . \Yii::$app->player->positionFullForm($model->position) . '</span>
                    </div>
                </div>
            </div>';
            },
        ],
        [
            'attribute' => 'position',
            'contentOptions' => ['style' => 'width:10%'],
            'filter' => ['D' => 'Defender', 'M' => 'Midfielder', 'G' => 'Goalkeeper', 'F' => 'Forward', 'Coach' => 'Coach'],
            'value' => function ($model) {
                return \Yii::$app->player->positionFullForm($model->position);
            },
        ],
        [
            'attribute' => 'avg_vote',
            'header' => 'Average vote',
            'filter' => false,
            'format' => 'html',
            'headerOptions' => ['style' => 'width:10%'],
            'value' => function ($model) use ($searchModel) {
                if (!empty($searchModel->league) && empty($searchModel->match_day)) {
                    return number_format(\Yii::$app->player->getAverageRateByLeague($searchModel->league, $model->player_id), 2);
                } else if (!empty($searchModel->match_day)) {
                    return number_format(\Yii::$app->player->getAverageRateByMatchDay($searchModel->match_day, $searchModel->season, $searchModel->league, $model->player_id), 2);
                } else {
                    return number_format(\Yii::$app->player->getAverageRate($model->player_id), 2);
                }
            },
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>