@extends('backend.layouts.master')
@section('content')

    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <div class="row">
        <div class="col-md-2">
            <p><a href="{{ url('admin/orders') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux commandes</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">

                    <h4>Période</h4>
                    <div class="row">
                        <form class="form-horizontal" action="{{ url('admin/order/rappels') }}" method="post">{!! csrf_field() !!}
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
                            <div class="col-lg-2 col-md-1 col-xs-12 text-left">
                                <div class="btn-group">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-filter"></i> &nbsp; Rechercher</button>
                                </div>
                            </div>
                        </form>
                        <div class="col-lg-6 col-md-3 col-xs-12">
                            <form action="{{ url('admin/order/rappel/generate') }}" method="POST" class="pull-right">
                                <input type="hidden" name="_method" value="POST">{!! csrf_field() !!}
                                <button type="submit" class="btn btn-warning"><i class="fa fa-file-o"></i> &nbsp;Générer tous les rappels</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h3><i class="fa fa-shopping-cart"></i> &nbsp;Rappel commandes du <span class="text-primary">{{ $helper->formatTwoDates($start,$end) }}</span></h3>

                    @include('backend.orders.partials.factures', ['orders' => $orders])

                </div>
            </div>
        </div>
    </div>

@stop