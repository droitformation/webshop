@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-info">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/archives') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-xs-12">
                                <div class="input-group">
                                    <?php $years = range(2011,data('Y')); ?>
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
                                    <?php $years = range(2011,data('Y')); ?>
                                    <span class="input-group-addon">Mois</span>
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

            <div class="panel panel-info">
                <div class="panel-body">

                    @if(!$inscriptions->isEmpty())

                        <?php
                            setlocale(LC_ALL, 'fr_FR.UTF-8');
                            $months = $inscriptions->sortBy('created_at')->groupBy(function ($item, $key) {
                                return $item->created_at->formatLocalized('%B');
                            });
                        ?>

                        <h4><i class="fa fa-users"></i> &nbsp;Inscriptions {{ $year }}</h4>
                        @foreach($months as $month => $list)
                            <h3 class="text-primary">
                                <a data-toggle="collapse" href="#collapseMonth_{{ $month }}"><strong>{{ ucfirst($month) }}</strong></a>
                            </h3>

                            <div class="collapse" id="collapseMonth_{{ $month }}">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="col-sm-3">Nom</th>
                                        <th class="col-sm-3">Participant</th>
                                        <th class="col-sm-3">N°</th>
                                        <th class="col-sm-3">Prix</th>
                                    </tr>
                                    </thead>
                                    <tbody class="selects">
                                        @foreach($list as $inscription)
                                            <tr>
                                                <td>
                                                    @if($inscription->inscrit)
                                                        <p><strong>{!! $inscription->inscrit->name !!}</strong></p>
                                                        <p>{!! $inscription->inscrit->email !!}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($inscription->participant)
                                                        {!! $inscription->participant->name  !!}
                                                    @endif
                                                </td>
                                                <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                                <td>{{ $inscription->price->price_cents }} CHF</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($inscriptions instanceof \Illuminate\Pagination\LengthAwarePaginator )
                                {{ $inscriptions->links() }}
                            @endif

                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>

@stop