@extends('backend.layouts.master')
@section('content')

    <?php $months = months_range(); ?>

    <div class="row">
        <div class="col-md-12">

            <h3>Archives des transactions</h3>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/archives') }}" method="post">
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
                            <div class="col-lg-2 col-md-2 col-xs-12" style="min-width:130px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Mois</span>
                                    <select class="form-control" name="month">
                                        @foreach($months as $number => $mois)
                                            <option {{ $number == $month ? 'selected' : '' }} value="{{ $number }}">{{ $mois }}</option>
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

                    <h4><i class="fa fa-list"></i> &nbsp;{{ ucfirst($model).'s' }} {{ $months[$month] }} {{ $year }}</h4>

                    @if(!$list->isEmpty())

                        <p><span class="label label-primary">Nombre {{ $list->count() }}</span></p>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="col-sm-3">Nom</th>
                                <th class="col-sm-3">
                                    <?php
                                    switch ($model) {
                                        case 'inscription':
                                            echo "Participant";
                                            break;
                                        case 'order':
                                            echo "Facture";
                                            break;
                                        case 'abo':
                                            echo "Abonnement";
                                            break;
                                    }
                                    ?>
                                </th>
                                <th class="col-sm-2">N°</th>
                                <th class="col-sm-2">Prix</th>
                                <th class="col-sm-2">Date</th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                                @foreach($list as $row)
                                   @include('backend.archives.partials.'.$model, ['row' => $row])
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Encore rien</p>
                    @endif

                </div>
            </div>

        </div>
    </div>

@stop