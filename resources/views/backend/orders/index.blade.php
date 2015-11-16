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
                                        <option value="">Tous</option>
                                        <option {{ old('status') == 'pending' ? 'selected' : '' }} value="pending">En attente</option>
                                        <option {{ old('status') == 'payed' ? 'selected' : '' }} value="payed">Payé</option>
                                        <option {{ old('status') == 'cancelled' ? 'selected' : '' }} value="cancelled">Annulé</option>
                                        <option {{ old('status') == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="exportOptions">
                            <h4>Champs</h4>
                            @if(!empty($names))
                                @foreach($names as $key => $name)
                                    <div class="checkbox-inline checkbox-border">
                                        <label>
                                            <input class="checkbox_all" {{ in_array($key,$columns) ? 'checked' : '' }} value="{{ $key }}" name="columns[]" type="checkbox"> {{ $name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                            <p style="margin-top: 10px;"><input type="checkbox" id="select_all" /> &nbsp;<span class="text-primary">Séléctionner tous</span></p>
                            <hr/>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-default" type="submit"><i class="fa fa-filter"></i> &nbsp; Filtrer la liste</button>
                                &nbsp;&nbsp;ou&nbsp;&nbsp;
                                <button class="btn btn-primary" name="export" value="1" type="submit"><i class="fa fa-download"></i> Télécharger [xls]</button>
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