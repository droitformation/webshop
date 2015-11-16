@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/orders') }}" method="post">

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
                            <div class="col-lg-2 col-md-3 col-xs-12" style="min-width:130px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Statut</span>
                                    <select class="form-control" name="status">
                                        <option {{ old('status') == 'pending' || !old('status') ? 'selected' : '' }} value="pending">En attente</option>
                                        <option {{ old('status') == 'payed' ? 'selected' : '' }} value="payed">Payé</option>
                                        <option {{ old('status') == 'cancelled' ? 'selected' : '' }} value="cancelled">Annulé</option>
                                        <option {{ old('status') == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-xs-12">
                                <span class="hidden-lg hidden-md"><hr/></span>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-default btn-sm" type="submit"><i class="fa fa-sort"></i> &nbsp; Trier</button>
                                    <button class="btn btn-info btn-sm" name="export" value="1" type="submit"><i class="fa fa-download"></i> Télécharger [xls]</button>
                                </div>
                            </div>
                        </div>

                        <div id="exportOptions">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Champs</h5>

                                    @if(!empty($columns))
                                        @foreach($columns as $id => $column)
                                            <div class="checkbox">
                                                <label>
                                                    <input value="{{ $id }}" name="columns[]" type="checkbox"> {{ $column }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <?php $helper = new \App\Droit\Helper\Helper(); ?>
                    <h3><i class="fa fa-shopping-cart"></i> &nbsp;
                        Commandes du <span class="text-primary">{{ $helper->formatTwoDates($start,$end) }}</span>
                    </h3>

                    @include('backend.orders.partials.commandes', ['orders' => $orders])

                </div>
            </div>

        </div>
    </div>

@stop