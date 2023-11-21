<?php if ($fixtures['status']) {?>
<?php foreach ($fixtures['data'] as $fixture) {
    ?>
<div class='col-xl-4 mb-5'>
    <div class='card card-custom gutter-b'>
        <div class='card-header'>
            <div class='p-5'>
                <h3 class='card-label'>Fixture
                    <small><?=$fixture['fixture']['id'];?></small>
                </h3>
            </div>
            <div class='card-toolbar p-3'>
                <?php if (!in_array($fixture['fixture']['id'], \Yii::$app->match->getMatchesBySeason($fixture['league']['season']))) {?>
                <a data-fixture-id="<?=$fixture['fixture']['id'];?>"
                    class='fetch-match btn btn-sm btn-success font-weight-bold'>
                    <i class='fa fa-download mr-2'></i>Fetch</a>
                <?php } else {?>
                <i class='fa fa-check text-success'></i>
                <?php }?>
            </div>
        </div>
        <div class='card-body'>
            <div style='display:flex; justify-content: space-between;'>
                <div>
                    <p class='font-size-sm'><?=date('M d, Y H:i:s', $fixture['fixture']['timestamp'])?></p>
                </div>
                <div>
                    <p class='mr-8 font-size-sm'><?=$fixture['league']['name'];?></p>
                </div>
                <div>
                    <p class='font-size-sm'><?=$fixture['league']['season'];?></p>
                </div>
            </div>
            <div style='display:flex; justify-content: space-between;'>
                <div>
                    <span class='navi-icon'>
                        <span class='svg-icon svg-icon-lg'>
                            <div class='symbol symbol-40 '>
                                <div class='symbol-label'
                                    style='background-image: url("<?=$fixture['teams']['home']['logo'];?> ")'>
                                </div>
                            </div>
                        </span>
                    </span>
                    <p style='text-align:center;' class='font-size-sm'>
                        <?=$fixture['teams']['home']['name'];?>
                    </p>
                </div>
                <div>
                    <span style='display:flex;justify-content:center;'>
                        <h3 class='display-5'><?=$fixture['goals']['home'];?></h3>
                        <h3 class='display-5'> : <?=$fixture['goals']['away'];?></h3>
                    </span>
                    <p style='text-align:center;' class='font-size-xs'>
                        <?=$fixture['fixture']['venue']['name'];?></p>
                </div>
                <div>
                    <span class='navi-icon'>
                        <span class='svg-icon svg-icon-lg'>
                            <div class='symbol symbol-40 '>
                                <div class='symbol-label'
                                    style='background-image: url("<?=$fixture['teams']['away']['logo'];?> ")'>
                                </div>
                            </div>
                        </span>
                    </span>
                    <p style='text-align:center;' class='font-size-sm'>
                        <?=$fixture['teams']['away']['name'];?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>
<?php } else {?>
<?=$leagues['message'];?>
<?php }?>