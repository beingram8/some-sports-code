<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserMatchVoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Match Votings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="d-flex flex-column mb-6">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                <div class="symbol-label"
                    style="background-image:url('<?=\Yii::$app->userData->photo($searchModel->user_id);?>')"></div>
            </div>
            <div>
                <a href="#"
                    class="font-weight-bolder font-size-h5"><?=\Yii::$app->userData->formatName($searchModel->user_id)?></a>
                <div class="text-muted"><?=\Yii::$app->userData->username($searchModel->user_id)?></div>
                <div class="mt-2">
                    <a href="<?=Url::toRoute(['/user/view', 'id' => $searchModel->user_id])?>"
                        class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">View</a>
                    <a href="<?=Url::toRoute(['user/index']);?>"
                        class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">
                        << Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::begin();?>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <?php echo Yii::$app->general->getFlash(); ?>
            <div class="card-body">
                <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'format' => 'raw',
            'value' => function ($model) use ($searchModel) {
                return '<a href="' . Url::to(['match-vote/index', 'UserMatchVoteSearch[match_id]' => $model->id, 'UserMatchVoteSearch[user_id]' => $searchModel->user_id]) . '" data-pjax=0 title="Voting Detail">
                ' . $model->id . '</a>';
            },
        ],
        'season',
        [
            'label' => 'Match Date',
            'attribute' => 'match_date',
            'filter' => DatePicker::widget([
                'name' => 'SeasonMatchSearch[match_date]',
                'value' => $searchModel->match_date,
                'options' => ['placeholder' => 'Select match date', 'autocomplete' => 'off'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
            ]),
            'value' => function ($model) {
                return $model->match_date;
            },
        ],
        [
            'attribute' => 'team_home_id',
            'format' => 'raw',
            'label' => 'Home Team',
            'filter' => Yii::$app->team->getTeams(),
            'value' => function ($model) {
                $winner = $model->team_home_id == $model->winner_team_id ? '<i class="fas fa-crown text-warning" title="Winner"></i>' : '';
                return '<div class="flex text-center">
                            ' . $winner . '
                        <div>'
                . Yii::$app->img->showImage($model->teamHome->logo) . '
                <div><div>' . $model->teamHome->name . '</div></div>';
            },
        ],
        [
            'attribute' => 'team_away_id',
            'label' => 'Away Team',
            'format' => 'raw',
            'filter' => Yii::$app->team->getTeams(),
            'value' => function ($model) {
                $winner = $model->team_away_id == $model->winner_team_id ? '<i class="fas fa-crown text-warning" title="Winner"></i>' : '';
                return '<div class="flex text-center">
                            ' . $winner . '
                        <div>'
                . Yii::$app->img->showImage($model->teamAway->logo) . '
                <div><div>' . $model->teamAway->name . '</div></div>';
            },
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>