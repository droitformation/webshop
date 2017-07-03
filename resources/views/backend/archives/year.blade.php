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
                <div class="panel-body">

                    <h4><i class="fa fa-list"></i> &nbsp;{{ ucfirst($model).'s' }} {{ $year }}</h4>

                    <script>
                        $( function() {
                            var data = {
                                // A labels array that can contain any sort of values
                                labels: <?php echo json_encode(array_values($months)); ?>,
                                // Our series array that contains series objects or in this case series data arrays
                                series: [<?php echo json_encode(array_values($list)); ?>]
                            };

                            var options = {
                                width: 600,
                                height: 450
                            };
                            // Create a new line chart object where as first parameter we pass in a selector
                            // that is resolving to our chart container element. The Second parameter is the actual data object.
                            new Chartist.Line('.ct-chart', data, options);
                        });
                    </script>
                    <div class="ct-chart ct-perfect-fourth"></div>
                </div>
            </div>

        </div>
    </div>

@stop