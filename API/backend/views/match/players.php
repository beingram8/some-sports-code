<?php
$this->title = $model->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="d-flex align-items-center flex-wrap mr-1">
            <div class="col-xl-12 w-100">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
                        <div class="px-2 py-3">
                            <h3 class="card-label mt-5">Match ID
                                <small><?=$model->id;?> | <?=$model->api_match_id;?></small>
                            </h3>
                        </div>
                        <div class="card-toolbar px-3">
                            <?=\yii\helpers\Html::a(Yii::t('app', '<< Back'),
    ['index'], ['data-pjax' => '0', 'class' => 'btn btn-primary font-weight-bold py-3 px-6'])?>
     <?=\yii\helpers\Html::a(Yii::t('app', 'Fetch Players'),
    ['match/fetch-player-manually', 'api_match_id' => $model->api_match_id], ['data-pjax' => '0', 'class' => 'ml-4 btn btn-primary font-weight-bold py-3 px-6'])?>
                        </div>
                    </div>
                    <div class="px-10 py-3">
                        <div style="display:flex; justify-content: space-between;">
                            <div>
                                <p class="font-size-h6 font-weight-bold">
                                    <?=date('Y-m-d H:i:s', $model->match_timestamp) . ' <span class="font-size-xs">(GMT + 2)</span>';?>
                                </p>
                            </div>
                            <div>
                                <p style="margin-right:190px;" class=" font-size-h6 font-weight-bold">
                                    <?=$model->league->name;?></p>
                            </div>
                            <div>
                                <p class="font-size-h6 font-weight-bold"><?=$model->season;?></p>
                            </div>
                        </div>
                        <div style="display:flex; justify-content: space-between;">
                            <div>
                                <span class="navi-icon">
                                    <span class="svg-icon svg-icon-lg">
                                        <div class="symbol symbol-40 ">
                                            <div class="symbol-label"
                                                style="background-image: url(<?=$model->teamHome->logo;?>)">
                                            </div>
                                        </div>
                                    </span>
                                </span>
                                <p style="text-align:center;" class="font-weight-bold">
                                    <?=$model->teamHome->name;?>
                                </p>
                            </div>
                            <div>
                                <span style="display:flex;justify-content:center;">
                                    <h3 class="display-5 font-weight-bold"> <?=$model->goal_of_home_team;?></h3>
                                    <h3 class="display-5 font-weight-bold"> : <?=$model->goal_of_away_team;?></h3>
                                </span>
                                <?=$model->match_ground;?></p>
                            </div>
                            <div>
                                <span class="navi-icon">
                                    <span class="svg-icon svg-icon-lg">
                                        <div class="symbol symbol-40 ">
                                            <div class="symbol-label"
                                                style="background-image: url(<?=$model->teamAway->logo;?>)">
                                            </div>
                                        </div>
                                    </span>
                                </span>
                                <p style="text-align:center;" class="font-size-sm">
                                    <?=$model->teamAway->name;?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="col-xl-12">
                <div class="">
                    <div class="">

                        <div class="row">
                            <?php
$teams = ['teamHome' => $model->team_home_id, 'teamAway' => $model->team_away_id];
if ($teams) {
    foreach ($teams as $k => $team_id) {

        ?>
                            <div class="col-xl-6 ">

                                <div class="card card-custom gutter-b">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center mr-2">
                                                <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                                    <div class="symbol-label">
                                                        <img src="<?=$model->$k->logo;?>" alt="" class="h-100">
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                        class="font-size-h3 text-dark-75 text-hover-primary font-weight-bolder">
                                                        <?=$model->$k->name;?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table id="<?=$k;?>" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Players</th>
                                                    <th>Avg Vote</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
$playersVote = \yii\helpers\ArrayHelper::map(\Yii::$app->match->playersWithAvgVoteInMatch($model->id, $team_id, 1), 'player_id', 'vote');

        $players = \Yii::$app->match->getMatchPlayer($model->id, $team_id, 1);
        if (!empty($players)) {?>
                                                <?php
foreach ($players as $player) {
            ?>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center mr-2">
                                                                <div
                                                                    class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                                                    <div class="symbol-label">
                                                                        <img src="<?=$player->player->photo;?>" alt=""
                                                                            class="h-100">
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#"
                                                                        class="font-size-sm text-dark-75 text-hover-primary font-weight-bolder">
                                                                        <?=$player->player->name;?>
                                                                    </a>
                                                                    <div
                                                                        class="font-size-sm text-muted font-weight-bold mt-1">



                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="<?=\yii\helpers\Url::to(['match-vote/index', 'match_id' => $model->id, 'player_id' => $player->player->id]);?>">
                                                            <?=isset($playersVote[$player->player->id]) ? number_format($playersVote[$player->player->id], 2) : "-";?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <?php } else {?>
                                                <tr>
                                                    <td colspan="2">
                                                        No data found
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                            <tbody>
                                                <tr class="bg-primary text-white">
                                                    <th colspan="2">Substitutes</th>
                                                </tr>
                                                <?php
$players = \Yii::$app->match->getMatchPlayer($model->id, $team_id, 2);
        if (!empty($players)) {?>
                                                <?php
foreach ($players as $player) {?>
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="d-flex align-items-center justify-content-between mb-2">
                                                            <div class="d-flex align-items-center mr-2">
                                                                <div
                                                                    class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                                                    <div class="symbol-label">
                                                                        <img src="<?=$player->player->photo;?>" alt=""
                                                                            class="h-100">
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#"
                                                                        class="font-size-sm text-dark-75 text-hover-primary font-weight-bolder">
                                                                        <?=$player->player->name;?>
                                                                    </a>
                                                                    <div
                                                                        class="font-size-sm text-muted font-weight-bold mt-1">

                                                                        <span>
                                                                            <?=$player->position;?>
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="<?=\yii\helpers\Url::to(['match-vote/index', 'match_id' => $model->id, 'player_id' => $player->player->id]);?>">
                                                            <?=isset($playersVote[$player->player->id]) ? number_format($playersVote[$player->player->id], 2) : "-";?>
                                                        </a>
                                                    </td>

                                                </tr>
                                                <?php }?>
                                                <?php } else {?>
                                                <tr>
                                                    <td colspan="2">
                                                        No data found
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                            <tbody>
                                                <tr class="bg-primary text-white">
                                                    <th colspan="2">Coach</th>
                                                </tr>
                                                <?php
$players = \Yii::$app->match->getMatchPlayer($model->id, $team_id, 3);
        if (!empty($players)) {?>
                                                <?php
foreach ($players as $player) {?>
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="d-flex align-items-center justify-content-between mb-2">
                                                            <div class="d-flex align-items-center mr-2">
                                                                <div
                                                                    class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                                                    <div class="symbol-label">
                                                                        <img src="<?=$player->player->photo;?>" alt=""
                                                                            class="h-100">
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#"
                                                                        class="font-size-sm text-dark-75 text-hover-primary font-weight-bolder">
                                                                        <?=$player->player->name;?>
                                                                    </a>
                                                                    <div
                                                                        class="font-size-sm text-muted font-weight-bold mt-1">

                                                                        <span>
                                                                            <?=$player->position;?>
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="<?=\yii\helpers\Url::to(['match-vote/index', 'match_id' => $model->id, 'player_id' => $player->player->id]);?>">
                                                            <?=isset($playersVote[$player->player->id]) ? number_format($playersVote[$player->player->id], 2) : "-";?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <?php } else {?>
                                                <tr>
                                                    <td colspan="2">
                                                        No data found
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php }}?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#teamHome').DataTable({
            paging: false,
            scrollY: 400,
            buttons: [{
                extend: 'searchBuilder',
                config: {
                    depthLimit: 2
                }
            }],
            dom: 'Bfrtip',
        });
        $('#teamAway').DataTable({
            paging: false,
            scrollY: 400,
            buttons: [{
                extend: 'searchBuilder',
                config: {
                    depthLimit: 2
                }
            }],
            dom: 'Bfrtip',
        });
    });
    </script>