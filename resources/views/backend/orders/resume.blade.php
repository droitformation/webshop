@extends('backend.layouts.master')
@section('content')

    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/orders/resume') }}" method="post">{!! csrf_field() !!}
                        <h4>Période</h4>
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Du</span>
                                    <input type="text" name="start" class="form-control datePicker" value="{{ $start->format('Y-m-d') }}" placeholder="Début">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">au</span>
                                    <input type="text" name="end" class="form-control datePicker" value="{{ $end->format('Y-m-d') }}" placeholder="Fin">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12" style="min-width:130px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Statut</span>
                                    <select class="form-control" name="status">
                                        <option value="">Tous</option>
                                        <option {{ old('status') == 'pending' ? 'selected' : '' }} value="pending">En attente</option>
                                        <option {{ old('status') == 'payed'  ? 'selected' : '' }} value="payed">Payé</option>
                                        <option {{ old('status') == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12" style="min-width:130px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Paquet</span>
                                    <select class="form-control" name="send">
                                        <option value="">Tous</option>
                                        <option {{ old('send') == 'payed' ? 'selected' : '' }} value="payed">Envoyés</option>
                                        <option {{ old('send') == 'pending' ? 'selected' : '' }} value="pending">En attente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 text-left">
                                <div class="btn-group">
                                    <button class="btn btn-default" type="submit">Rechercher</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes du <span class="text-primary">{{ $helper->formatTwoDates($start,$end) }}</span></h3>
                    <h4><strong>Status payement</strong>: {{ $status_list[$status] }}</h4>
                    <h4><strong>Status envoi</strong>: {{ $send_list[$send] }}</h4>

                    <?php
                    $count = $orders->map(function ($item, $key) {
                        return $item->products->count();
                    })->sum();

                    $money = new \App\Droit\Shop\Product\Entities\Money;

                    $sum = $orders->map(function ($item, $key) {
                        return $item->total_sum;
                    })->sum();

                    $grouped = $orders->groupBy(function ($order, $key) {
                        return $order->products->count();
                    })->map(function ($orders, $key) {
                        return $orders->count();
                    });
                    ?>

                    <div class="row">
                        <div class="col-md-4">

                            <table class="table table-condensed table-bordered" style="margin-bottom: 10px;">
                                <thead>
                                    <tr>
                                        <th><strong>Nbr de paquets</strong></th>
                                        <th><strong>Nbr ouvrages</strong> <small class="text-muted">(par paquet)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$grouped->isEmpty())
                                        @foreach($grouped as $nbr => $group)
                                            <tr><td>{{ $group }}</td><td>{{ $nbr }}</td></tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="2" class="text-center">Encore aucun paquet à envoyer</td></tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-5">
                            <div class="well well-sm">
                                <dl class="dl-horizontal dl-resume">
                                    <dt>Nombre de commandes :</dt><dd>{{ $orders->count() }}</dd>
                                    <dt>Nombre d'ouvrages : </dt><dd>{{ $count }}</dd>
                                    <dt>Total (avec frais de port) : </dt><dd>{{ $money->format($sum) }} CHF</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@stop