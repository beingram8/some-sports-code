<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">

<head>
    <meta charset="<?=Yii::$app->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <link rel="icon" type="image/x-icon" href="">

    <?php $this->head()?>
</head>

<body>
    <?php $this->beginBody()?>

    <body id="kt_body"
        class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled page-loading">

        <!--end::Header Mobile-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Page-->
            <div class="error error-3 d-flex flex-row-fluid bgi-size-cover bgi-position-center">
                <!--begin::Wrapper-->
                <div class="px-10 px-md-30 py-10 py-md-0 d-flex flex-column justify-content-md-center">
                    <?php echo $content; ?>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>

    </body>


    <?php $this->endBody()?>
</body>

</html>
<?php $this->endPage()?>