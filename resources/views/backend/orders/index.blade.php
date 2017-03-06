 @extends('backend.layouts.master')
@section('content')

    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <p><a href="{{ url('admin/order/rappels') }}" class="btn btn-brown"><i class="fa fa-exclamation-triangle"></i> &nbsp;Voir les rappels</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/orders') }}" method="post">{!! csrf_field() !!}
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
                                        <option {{ old('status') == 'payed'  ? 'selected' : '' }} value="payed">Payé</option>
                                        <option {{ old('status') == 'gratuit' ? 'selected' : '' }} value="gratuit">Gratuit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12" style="min-width:130px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Facture</span>
                                    <select class="form-control" name="send">
                                        <option value="">Tous</option>
                                        <option {{ old('send') == 'send' ? 'selected' : '' }} value="send">Envoyés</option>
                                        <option {{ old('send') == 'pending' ? 'selected' : '' }} value="pending">En attente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-1 col-xs-12 text-left">
                                <div class="btn-group">
                                    <button class="btn btn-default" type="submit"><i class="fa fa-filter"></i> &nbsp; Rechercher</button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-xs-12" style="min-width:130px;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="order_no" placeholder="Recherche par numéro...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
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
                                        <input type="checkbox" {{ old('onlyfree') == 1 ? 'checked' : '' }} name="onlyfree" value="1">
                                        <i class="fa fa-star"></i>&nbsp;&nbsp;Que livres gratuits &nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" {{ old('details') == 1 ? 'checked' : '' }} name="details" value="1">
                                        <i class="fa fa-list"></i>&nbsp;&nbsp;Détail des commandes &nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="exportOptions" class="collapse">

                            @if(!empty(config('columns.names')))
                                <p><label><input type="checkbox" id="select_all"> Tout séléctionner</label></p>
                                @foreach(config('columns.names') as $key => $name)
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

            @if(!$invalid->isEmpty())
                <div class="panel panel-midnightblue">
                    <div class="panel-body">
                        <h3>Commandes invalides</h3>
                        <p>Il manque probablement l'adresse ou l'utilisateur, ou l'adresse n'est pas de type contact</p>
                        <ul class="list-group">
                            @foreach($invalid as $commande)
                                <li class="list-group-item"><strong>No:</strong> {{ $commande->order_no }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    @if(old('order_no'))
                        <h3><i class="fa fa-shopping-cart"></i> &nbsp;Recherche <span class="text-primary">{{ old('order_no') }}</span></h3>
                    @else
                        <h3><i class="fa fa-shopping-cart"></i> &nbsp;Commandes du <span class="text-primary">{{ $helper->formatTwoDates($start,$end) }}</span></h3>
                    @endif

                    @if(old('$status'))
                        <h4>Status {{ $status_list[old('$status')] }}</h4>
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