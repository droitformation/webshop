@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <div id="appComponent">
                        <form class="form-horizontal" action="{{ url('admin/statistique') }}" method="post">{!! csrf_field() !!}
                            <statistique-filter :search="{{ json_encode($search) }}"></statistique-filter>
                        </form>
                    </div>

                    @if($results)
                        <?php

                        $data = $results->map(function ($item, $key) {
                            return ['x' => $key, 'y' => $item['results']];
                        })->values()->toArray();

                        echo '<pre>';
                        print_r($data);
                        echo '</pre>';
                        ?>
                    @endif


                    <script>
                        window.onload = function () {
                            var dataPoints = [];

                            var chart = new CanvasJS.Chart("chartContainer", {
                                animationEnabled: true,
                                theme: "light2",
                                zoomEnabled: true,
                                title: {text: "Price"},
                                axisY: {
                                    title: "Price",
                                    titleFontSize: 24,
                                    prefix: "CHF"
                                },
                                data: [{
                                    type: "line",
                                    yValueFormatString: "CHF#,##0.00",
                                    dataPoints: dataPoints
                                }]
                            });

                            function addData(data) {
                                var dps = data;
                                for (var i = 0; i < dps.length; i++) {
                                    dataPoints.push({
                                        x: new Date(dps[i]['x']),
                                        y: dps[i]['y']
                                    });
                                }
                                chart.render();
                            }

                            addData(<?php echo json_encode($data); ?>)
                        }
                    </script>

                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
            </div>

        </div>
    </div>

@stop