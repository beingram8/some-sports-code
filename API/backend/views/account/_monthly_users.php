<?php
use miloschuman\highcharts\Highcharts;
?>
<div class="box box-primary">
    <?php echo Highcharts::widget([
    'options' => [
        'chart' => [
            'type' => 'column',
        ],
        'title' => [
            'text' => 'New Users Registration',
        ],
        'accessibility' => [
            'announceNewData' => [
                'enabled' => true,
            ],
        ],
        'xAxis' => [
            'type' => 'category',
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Total number of new registered users',
            ],
        ],
        'legend' => [
            'enabled' => false,
        ],
        'plotOptions' => [
            'series' => [
                'borderWidth' => 0,
            ],
        ],
        'credits' => ['enabled' => false],
        'series' => [
            [
                'name' => "Register chart",
                'colorByPoint' => true,
                'data' => $data,
            ],
        ],
    ],
]);

?>
</div>