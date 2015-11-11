@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-10 col-xs-8">
                            <form class="form-horizontal" action="{{ url('admin/orders') }}" method="post">
                                <div class="col-md-1 col-xs-3">
                                    <input type="text" name="start" class="form-control datePicker" value="{{ old('start') }}" placeholder="Début">
                                </div>
                                <div class="col-md-1 col-xs-3">
                                    <input type="text" name="end" class="form-control datePicker" value="{{ old('end') }}" placeholder="Fin">
                                </div>
                                <div class="col-md-1 col-xs-3" style="min-width:130px;">
                                    <select class="form-control" name="status">
                                        <option value="">Statut</option>
                                        <option {{ old('status') == 'pending' ? 'selected' : '' }} value="pending">En attente</option>
                                        <option {{ old('status') == 'payed' ? 'selected' : '' }} value="payed">Payé</option>
                                        <option {{ old('status') == 'cancelled' ? 'selected' : '' }} value="cancelled">Annulé</option>
                                        <option {{ old('status') == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-xs-6">
                                    <div class="btn-group">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-sort"></i> &nbsp; Trier</button>
                                        <button class="btn btn-info" name="export" value="1" type="submit"><i class="fa fa-download"></i> &nbsp; Télécharger [xls]</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2 col-xs-4"></div>
                    </div>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes</h3>

                    @include('backend.users.partials.commandes', ['orders' => $orders])

                </div>
            </div>

        </div>
    </div>

@stop