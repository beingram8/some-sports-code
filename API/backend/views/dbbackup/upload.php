<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\widgets\FileInput;
use kartik\file\FileInput;
use yii\widgets\Breadcrumbs;	

$this->title = "Upload DB File";
$this->params ['breadcrumbs'] [] = [ 
		'label' => 'Manage',
		'url' => array (
				'index' 
		) 
];
$this->params['breadcrumbs'][]= [
'label'	=> 'Restore',
'url'	=> array('restore'),
];?>


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

        <!--end::Toolbar-->
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-body">

                <div class="backup-default-index">


                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


                    <?= $form->field($model, 'upload_file')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'mysql/*.sql'],
                    ]); ?>


                    <div class="form-group">
                        <?=
                        Html::submitButton( 'Save' ,
                            ['class' => 'btn btn-success']
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>


            </div>
        </div>
    </div>
</div>