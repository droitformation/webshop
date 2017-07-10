@extends('backend.layouts.master')
@section('content')

    <?php $months = months_range(); ?>

    <div class="row">
        <div class="col-md-12">

            <h3>Statistiques des transactions</h3>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/archive/year') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Archives pour</span>
                                    <select class="form-control" name="model">
                                        <option {{ $model == 'inscription' ? 'selected' : '' }} value="inscription">Inscriptions</option>
                                        <option {{ $model == 'order' ? 'selected' : '' }} value="order">Commandes</option>
                                        <option {{ $model == 'abo' ? 'selected' : '' }} value="abo">Abonnement</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-xs-12">
                                <div class="input-group">
                                    <?php $years = range(2011,date('Y')); ?>
                                    <span class="input-group-addon">Année</span>
                                    <select class="form-control" name="year">
                                        <option value="">Choisir</option>
                                        @foreach($years as $annee)
                                            <option {{ $year == $annee ? 'selected' : '' }} value="{{ $annee }}">{{ $annee }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-1 col-xs-12 text-left">
                                <div class="btn-group">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-filter"></i> &nbsp;Rechercher</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary">
                <div class="panel-body" id="appComponent">

                    <h4><i class="fa fa-list"></i> &nbsp;{{ ucfirst($model).'s' }} {{ $year }}</h4>

                    <ul class="chart-description">
                        @foreach($colors as $letter => $color)
                            <li>
                                <input checked class="year-item" type="checkbox" value="{{ $color['year'] }}">
                                <span style="background: {{ $color['color'] }}; color: #fff;">
                                    &nbsp;{{ $color['year'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    @if(!empty($list))

                        <script>
                            $( function() {

                                var data = {
                                    labels: <?php echo json_encode(array_values($months)); ?>,
                                    series: <?php echo json_encode(array_values($list)); ?>,
                                };

                                var options = {stretch: true, low: 1, height: '450px'};
                                var chart   = new Chartist.Line('.ct-chart', data, options);
                                var colors  = <?php echo json_encode(array_values($colors)); ?>;

                                chart.on('draw', function(context) {
                                    // First we want to make sure that only do something when the draw event is for bars. Draw events do get fired for labels and grids too.
                                    if(context.type === 'line' || context.type === 'point') {

                                        var max = Math.max(...context.series);
                                        // With the Chartist.Svg API we can easily set an attribute on our bar that just got drawn
                                        var index = context.type === 'line' ? context.index : context.seriesIndex;

                                        var color  = Object.values(colors)[index].color;
                                        color  = max > 0 ? color : '#fff';

                                        if(context.type === 'line'){
                                            var stroke = 2;
                                            stroke = max > 0 ? stroke : 0;
                                            context.element.attr({ style: 'stroke: ' + color + '; stroke-width : ' + stroke + 'px;'});
                                        }
                                        else{
                                            var stroke = 10;
                                            stroke = max > 0 ? stroke : 0;
                                            context.element.attr({ style: 'stroke: ' + color + '; stroke-width : ' + stroke + 'px;' });
                                        }
                                    }
                                });

                                $( ".year-item" ).change(function() {

                                    var allkeys = [];
                                    var selected = $('.chart-description input:checkbox:checked').map(function() {return this.value;}).get();
                                    var series   = <?php echo json_encode($list); ?>;
                                    var years    = <?php echo json_encode(array_values($list)); ?>;
                                    var empty    = Array.apply(null, Array(12)).map(Number.prototype.valueOf,0);

                                    Object.keys(series).forEach(function(key) {
                                        allkeys.push(key);
                                    });

                                    allkeys.forEach(function(year,index) {
                                        years[index] = selected.includes(year) ? series[year] : empty;
                                    });

                                    years = years.filter(function(){return true;});

                                    var data = {
                                        labels: <?php echo json_encode(array_values($months)); ?>,
                                        series: years,
                                    };

                                    chart.update(data);
                                });

                            });
                        </script>
                        <div class="ct-chart ct-perfect-fourth"></div>

                    @else
                        <p>Aucun résultat pour cette année</p>
                    @endif

                </div>
            </div>

        </div>
    </div>

@stop