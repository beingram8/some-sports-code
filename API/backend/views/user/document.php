<?php 

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Users Document');
$this->params['breadcrumbs'][] = $this->title;
$levelData = Yii::$app->userData->getLevelList();
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
    </div>
</div>
<?php Pjax::begin();?>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <?php echo Yii::$app->general->getFlash(); ?>
            <div class="card-body">
                <?=GridView::widget([
    'id' => 'parent-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [

        // 'id',
        [
            'attribute' => 'id',
            'contentOptions' => ['style' => 'width:150px'],
        ],
        [
            'attribute' => 'document',
            'contentOptions' => ['style' => 'width:250px'],
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->document && isset($model->document)) {
                    return '<div class="flex text-center"><div>' . Yii::$app->img->showImage($model->document) . '</div></div>';
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'user',
            'format' => 'html',
            'contentOptions' => ['style' => 'width:450px'],
            'label' => 'User Info',
            'value' => function ($model) {
                return '<div class="d-flex align-items-center justify-content-between mb-5">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle symbol-50 mr-3">
                        <img alt="Pic" src="' . Yii::$app->userData->photo($model->user_id) . '"></div>
                    <div class="d-flex flex-column">
                        <a href="" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">' . $model->user->first_name . ' ' . $model->user->last_name . '</a>
                        <span class="text-muted font-weight-bold font-size-sm">@' . $model->user->username . '</span>
                    </div>
                </div>
                </div>';
                return $model->user->first_name . ' ' . $model->user->last_name;
            },
        ],
        
        [
            'attribute' => 'is_confirm',
            'filter' => ['1' => 'Yes','0' => 'No'],
            'format' => 'raw',
            'value' => function ($model) {
                $url = \yii\helpers\Url::to(['user/update-document'], $schema = true);
                return '<form class="ajax-form" action="' . $url . '">' .
                \yii\helpers\Html::dropDownList('is_confirm', $model->is_confirm, ['1' => 'Yes','0' => 'No'], ['prompt' => '', 'class' => 'document_status form-control', 'data-id' => $model->id, 'data-option' => $model->is_confirm]) . '
                        </form>';
            },
        ],
        [
            'attribute' => 'created_at',
            'filter' => DatePicker::widget([
                'name' => 'ParentConfirmationSearch[created_at]',
                'value' => $searchModel->created_at,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd M yyyy',
                    'endDate' => "0d",
                ],
                'options' => [
                    'autoComplete' => 'off',
                    'placeholder' => 'Select Created At',
                ],
            ]),
            'value' => function ($model) {
                return Yii::$app->general->format_date($model->created_at);
            },
        ],
    ],
]);?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end();?>