<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

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
    <title>
        <?=\Yii::$app->params['app_name'];?> - <?=\Yii::t('app', ' Backend');?> |
        <?=Html::encode($this->title)?></title>
    <link rel="icon" type="image/x-icon" href="<?=Url::base() . '/../img_assets/fan-rating.png'?>">
    <?php $this->head()?>
</head>

<?php $this->beginBody()?>

<body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled">
    <div class="d-flex flex-column flex-root">
        <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white"
            id="kt_login">
            <div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10"
                style="background:#e10d12">
                <div class="d-flex flex-row-fluid flex-column justify-content-between">
                    <a class="flex-column-auto mt-5" href="">
                        <img alt="Logo" class="max-h-70px" src="<?=Url::base() . '/../img_assets/fan-rating.png'?>">
                    </a>
                    <div class="flex-column-fluid d-flex flex-column justify-content-center">
                        <h3 class="font-size-h1 mb-5 text-white">
                            <?php echo Yii::t('app', 'Fan Rating Admin Panel'); ?></h3>
                        <p class="font-weight-lighter text-white opacity-80">
                            <?php echo Yii::t('app', 'Backend'); ?></p>
                    </div>
                    <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
                        <div class="opacity-70 font-weight-bold text-white">©
                            <?php echo date('Y') . ' '; ?><?=\Yii::$app->params['app_name'];?></div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
                <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                    <?=$content?>
                </div>
            </div>
        </div>
    </div>
</body>
<?php $this->endBody()?>

</html>
<?php $this->endPage()?>