<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['/site/verify-email', 'token' => $user->verification_token]);
?>
<div class="sc-htpNat cpoSvg" color="gray1" scale="1"
    style="color: #052D49; font-family: America, sans-serif; -webkit-letter-spacing: inherit; -moz-letter-spacing: inherit; -ms-letter-spacing: inherit; letter-spacing: inherit; margin: 0; opacity: 1; position: relative; text-align: left; text-transform: inherit; text-shadow: none; -webkit-transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); -webkit-user-select: inherit; -moz-user-select: inherit; -ms-user-select: inherit; user-select: inherit; font-size: 1rem; font-weight: 400; line-height: 1.5;">
    <span class="sc-bxivhb eWjzRU" color="gray3" scale="1" size="2"
        style="color: #4F687A; font-family: America,sans-serif; -webkit-letter-spacing: inherit; -moz-letter-spacing: inherit; -ms-letter-spacing: inherit; letter-spacing: inherit; margin: 0; opacity: 1; position: relative; text-align: inherit; text-transform: inherit; text-shadow: none; -webkit-transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); -webkit-user-select: inherit; -moz-user-select: inherit; -ms-user-select: inherit; user-select: inherit; font-size: 1.3090000000000002rem; font-weight: 400; line-height: 1.25;">
        <p>Hello <?=Html::encode($userData->first_name)?>,<br></br>
            <?=Yii::t('app', 'Please verify your account');?> </p>
    </span>

    <div class="sc-bdVaJa dggHpc" display="block"
        style="box-sizing: border-box; position: static; border-radius: 0; -webkit-transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); overflow: inherit; padding: 0rem 0rem 0rem 0rem; margin: 1.5rem 0rem 1.5rem 0rem; border-top: none; border-right: none; border-bottom: none; border-left: none; display: block;">
        <?=\Yii::t('app', 'Follow the link below to verify your email');?></div>
</div>
<a class="sc-jzJRlG hznfox" color="primary" type="button" href="<?=$verifyLink;?>" role="button"
    style="-webkit-backface-visibility: hidden; backface-visibility: hidden; background-color: #0b4776;
     border: 2px solid #0b4776; border-radius: 4px; box-sizing: border-box;
     display: inline-block; font-weight: 700; padding: 1rem 1.5rem; position: relative; text-align: center; text-decoration: none; text-transform: uppercase; -webkit-transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); transition: all 300ms cubic-bezier(0.19, 1, 0.22, 1); -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; white-space: inherit; cursor: pointer; color: #FFFFFF; font-size: 1rem;">
    <div class="sc-fjdhpX ktKmOq" style="visibility: visible;"><?=\Yii::t('app', 'Activate your account');?></div>
</a>