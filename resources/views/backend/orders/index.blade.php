 @extends('backend.layouts.master')
@section('content')

    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/orders') }}" method="post">
                        {!! csrf_field() !!}
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
                                        <option {{ old('status') == 'pending'  || $status == 'pending' ? 'selected' : '' }} value="pending">En attente</option>
                                        <option {{ old('status') == 'payed'    || $status == 'payed'? 'selected' : '' }} value="payed">Payé</option>
                                        <option {{ old('status') == 'gratuit'  || $status == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-1 col-xs-12 text-left">
                                <div class="btn-group">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-filter"></i> &nbsp; Rechercher</button>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <h4>Filtres</h4>
                        <div class="row">
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-primary" role="button" data-toggle="collapse" href="#exportOptions">Choisir les champs</a>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" {{ old('onlyfree') || $onlyfree == 1 ? 'checked' : '' }} name="onlyfree" value="1">
                                        <i class="fa fa-star"></i>&nbsp;&nbsp;Que livres gratuits &nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" {{ old('details') || $details == 1 ? 'checked' : '' }} name="details" value="1">
                                        <i class="fa fa-list"></i>&nbsp;&nbsp;Détail des commandes &nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="exportOptions" class="collapse">
                            @if(!empty($names))
                                @foreach($names as $key => $name)
                                    <div class="checkbox-inline checkbox-border">
                                        <label>
                                            <input class="checkbox_all" {{ in_array($key,$columns) ? 'checked' : '' }} value="{{ $name }}" name="columns[{{ $key }}]" type="checkbox"> {{ $name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                            <p style="margin-top: 10px;"><input type="checkbox" id="select_all" /> &nbsp;<span class="text-primary">Séléctionner tous</span></p>
                        </div>

                        <button class="btn btn-primary pull-right" name="export" value="1" type="submit"><i class="fa fa-download"></i> Télécharger [xls]</button>
                    </form>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes du <span class="text-primary">{{ $helper->formatTwoDates($start,$end) }}</span></h3>
                    @if($status)
                        <h4>Status {{ $status_list[$status] }}</h4>
                    @endif

                    @include('backend.orders.partials.commandes', ['orders' => $orders, 'cancelled' => false])

                    <hr/>

                    <p><a class="btn btn-warning btn-sm pull-right" data-toggle="collapse" href="#cancelTable">Annulations</a></p>
                </div>
            </div>

            <div id="cancelTable" class="collapse">
                <div class="panel panel-warning">
                    <div class="panel-body">

                        <h3><i class="fa fa-times"></i> &nbsp;Commandes annulés du <span class="text-primary">{{ $helper->formatTwoDates($start,$end) }}</span></h3>

                        @include('backend.orders.partials.commandes', ['orders' => $cancelled, 'cancelled' => true])

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop