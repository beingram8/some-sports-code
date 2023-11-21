<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Transaction Detail');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subheader py-2 py-lg-12 subheader-transparent">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex flex-column mb-6">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                    <div class="symbol-label"
                        style="background-image:url('<?=\Yii::$app->userData->photo($searchModel->user_id);?>')">
                    </div>
                </div>
                <div>
                    <a href="#"
                        class=" text-white font-weight-bolder font-size-h5"><?=\Yii::$app->userData->formatName($searchModel->user_id)?>(<?=\Yii::$app->userData->username($searchModel->user_id)?>)</a>
                    <div class="text-white">Total Token :
                        <?=\Yii::$app->userData->totalToken($searchModel->user_id)?>
                    </div>
                    <div class="mt-2">
                        <a data-pjax=0 href="<?=Url::toRoute(['/user/view', 'id' => $searchModel->user_id])?>"
                            class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">View</a>
                        <a data-pjax=0 href="<?=Url::toRoute(['user/index']);?>"
                            class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">
                            << Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <?=Html::a(Yii::t('app', 'Manage User Token'),
    ['add-token', 'user_id' => $searchModel->user_id], ['data-pjax' => '0', 'class' => 'btn btn-white font-weight-bold py-3 px-6'])?>
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
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'transaction_type',
            'format' => 'raw',
            'filter' => [10 => 'Credited', 20 => 'Debited'],
            'value' => function ($model) {
                if ($model->transaction_type == 10) {
                    return '<span class="label label-success label-pill label-inline mr-2">' . Yii::t('app', 'Credited') . '</span>';
                } else {
                    return '<span class="label label-danger label-pill label-inline mr-2">' . Yii::t('app', 'Debited') . '</span>';
                }
            },
        ],
        [
            'attribute' => 'token_type_id',
            'label' => 'Token Type',
            'value' => function ($model) {
                return isset($model->tokenType->name) ? $model->tokenType->name : '-';
            },
        ],
        [
            'attribute' => 'token',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->transaction_type == 10) {
                    return '<p class="text-success font-weight-bolder">+ ' . $model->token . '</p>';
                } else {
                    return '<p class="text-danger font-weight-bolder">- ' . $model->token . '</p>';
                }
            },
        ],
        [
            'attribute' => 'remark',
            'value' => function ($model) {
                return !empty($model->remark) ? $model->remark : '-';
            },
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Created At',
            'filter' => DatePicker::widget([
                'name' => 'UserTokenTransactionSearch[created_at]',
                'value' => $searchModel->created_at,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'endDate' => "0d",
                ],
                'options' => [
                    'autoComplete' => 'off',
                    'placeholder' => 'Select Created At',
                ],
            ]),
            'value' => function ($model) {
                return Yii::$app->time->asDatetime($model->created_at);
            },
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>