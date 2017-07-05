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
                                <input checked class="year-item" type="checkbox" value="{{ $color['year'] }}"> &nbsp;{{ $color['year'] }}
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

                                var options = {stretch: true, low: 0, height: '450px'};
                                var chart   = new Chartist.Line('.ct-chart', data, options);

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
                                        if(selected.includes(year)) {
                                            years[index] = series[year];
                                        }else{
                                            years[index] = empty;
                                        }
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