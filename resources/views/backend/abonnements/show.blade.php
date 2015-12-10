@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <a href="{{ url('admin/abonnements/'.$abonnement->abo_id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Editer abonnement</h4>
                </div>
                <form action="{{ url('admin/abonnement/'.$abonnement->id) }}" method="POST" class="form-horizontal">

                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Numéro</label>
                            <div class="col-sm-4 col-xs-5">
                                <input type="text" class="form-control" value="{{ $abonnement->numero }}" name="numero">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label">Adresse</label>
                            <div class="col-sm-8 col-xs-12">

                                <!-- Autocomplete for adresse -->
                                <div class="autocomplete-wrapper">
                                    <div class="input-adresse" data-adresse="{{ $abonnement->adresse_id }}" data-type="adresse_id">
                                        <input type="hidden" class="form-control" value="{{ $abonnement->adresse_id }}" name="adresse_id">
                                    </div>
                                    <div class="choice-adresse"></div>
                                    <div class="collapse adresse-find">
                                        <div class="form-group">
                                            <input id="search-adresse1" class="form-control search-adresse" placeholder="Chercher une adresse..." type="text">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Autocomplete for adresse -->

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tiers payant</label>
                            <div class="col-sm-4 col-xs-5">

                                <!-- Autocomplete for tiers adresse -->
                                <div class="autocomplete-wrapper">
                                    <div class="input-adresse" data-adresse="{{ $abonnement->tiers_id }}" data-type="tiers_id">
                                        <input type="hidden" class="form-control" value="{{ $abonnement->tiers_id }}" name="tiers_id">
                                    </div>
                                    <div class="choice-adresse"></div>
                                    <div class="collapse in adresse-find">
                                        <div class="form-group">
                                            <input id="search-adresse2" class="form-control search-adresse" placeholder="Chercher une adresse..." type="text">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Autocomplete for tiers adresse -->

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix spécial</label>
                            <div class="col-sm-4 col-xs-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $abonnement->price }}" name="price">
                                    <span class="input-group-addon">CHF</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Exemplaires</label>
                            <div class="col-sm-4 col-xs-5">
                                <input type="text" class="form-control" value="{{ $abonnement->exemplaires }}" name="exemplaires">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Renouvellement</label>
                            <div class="col-sm-4 col-xs-8">
                                <select class="form-control" name="renouvellement">
                                    <option {{ ($abonnement->renouvellement == 'auto' ?  'selected' : '') }} value="auto">Auto</option>
                                    <option {{ ($abonnement->renouvellement == 'year' ? 'selected' : '') }} value="year">1 an</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Statut</label>
                            <div class="col-sm-4 col-xs-8">
                                <select class="form-control" name="status">
                                    <option {{ ($abonnement->status == 'abonne' ?  'selected' : '') }} value="abonne">Abonné</option>
                                    <option {{ ($abonnement->status == 'gratuit' ? 'selected' : '') }} value="gratuit">Gratuit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Réference</label>
                            <div class="col-sm-8 col-xs-8">
                                <input type="text" class="form-control" value="{{ $abonnement->reference }}" name="reference">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Remarque</label>
                            <div class="col-sm-8 col-xs-12">
                                <textarea class="form-control redactorSimple" name="remarque">{{ $abonnement->remarque }}</textarea>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $abonnement->id }}" name="id">
                        <input type="hidden" value="{{ $abonnement->abo_id }}" name="abo_id">

                    </div>
                    <div class="panel-footer text-right"><button type="submit" class="btn btn-info">Envoyer</button></div>
                </form>

            </div>
        </div>
        <div class="col-md-6">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-star-half-empty"></i> &nbsp;Payements</h4>
                </div>
                <div class="panel-body">

                    @if(isset($abonnement->factures))
                        <?php $groupes = $abonnement->factures->groupBy('product_id'); ?>
                    @endif

                    <?php $abonnement->abo->load('products'); ?>

                    @if(isset($abonnement->abo->products))
                        <?php $products = $abonnement->abo->products->sortByDesc('created_at'); ?>
                        @foreach($products as $product)

                            <div class="well well-sm">
                                <h4><i class="fa fa-stop-circle-o"></i> &nbsp;{{ $product->title }}</h4>

                                @if(isset($groupes[$product->id]))
                                    <div class="row">
                                        <div class="col-md-6">
                                        @foreach($groupes[$product->id] as $facture)

                                            <?php $facture->load('rappels'); ?>

                                            <!-- Payed -->
                                            @if($facture->payed_at)
                                                <p>
                                                    <span class="label label-success"><i class="fa fa-star"></i></span>&nbsp;&nbsp;
                                                    <strong>Payé le {!! $facture->payed_at->formatLocalized('%d %B %Y') !!}</strong>
                                                </p>

                                                @if($facture->abo_facture)
                                                    <a class="btn btn-sm btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
                                                @endif

                                                @include('backend.abonnements.partials.payement', ['payement' => $facture, 'type' => 'facture'])
                                            @else
                                                <p>
                                                    <span class="label label-default"><i class="fa fa-star"></i></span>&nbsp;&nbsp;
                                                    <strong>En attente: {{ $facture->created_at->formatLocalized('%d %B %Y') }}</strong>
                                                </p>

                                                @if($facture->abo_facture)
                                                    <a class="btn btn-sm btn-default" target="_blank" href="{{ asset($facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;Facture pdf</a>
                                                @endif

                                                <a data-toggle="collapse" href="#payInvoice_{{ $facture->id }}" class="btn btn-info btn-sm">Marquer payé</a>

                                                <form action="{{ url('admin/facture') }}" method="POST" class="pull-right">{!! csrf_field() !!}
                                                    <input type="hidden" value="{{ $facture->id }}" name="abo_facture_id">
                                                    <input type="hidden" value="rappel" name="type">
                                                    <button class="btn btn-sm btn-warning" type="submit">Créer un rappel</button>
                                                </form>

                                                <div class="collapse" id="payInvoice_{{ $facture->id }}">
                                                    <form action="{{ url('admin/facture/'.$facture->id) }}" method="POST">
                                                        <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
                                                        <input type="hidden" value="{{ $facture->id }}" name="id">
                                                        <div class="form-group input-group">
                                                            <input type="text" class="form-control datePicker" name="payed_at" placeholder="Payé le">
                                                            <span class="input-group-btn"><button class="btn btn-info" type="submit">Ok</button></span>
                                                        </div>
                                                    </form>
                                                </div>

                                            @endif
                                            <!-- End Payed -->

                                        @endforeach
                                        </div>

                                        @if(!$facture->rappels->isEmpty())
                                            <div class="col-md-6">

                                                <!-- Rappels -->
                                                @foreach($facture->rappels as $rappel)
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <span class="label label-warning"><i class="fa fa-star"></i></span>&nbsp;&nbsp;
                                                            <strong>Rappel le {!! $rappel->created_at->formatLocalized('%d %B %Y') !!}</strong>
                                                        </div>
                                                        <div class="col-md-3">
                                                            @include('backend.abonnements.partials.payement', ['payement' => $rappel, 'type' => 'rappel'])
                                                        </div>
                                                    </div>
                                                @endforeach

                                                @if($facture->rappels->first()->abo_rappel)
                                                    <a class="btn btn-sm btn-default" target="_blank" href="{{ asset($facture->rappels->first()->abo_rappel) }}"><i class="fa fa-file"></i> &nbsp;Rappel pdf</a>
                                                @endif
                                                <!-- End Rappels -->

                                            </div>
                                        @endif

                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <hr/>
                    <h4>Créer une facture</h4>
                    <form action="{{ url('admin/facture') }}" method="POST">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-4"><input type="text" class="form-control datePicker" name="created_at" placeholder="Date"></div>
                            <div class="col-md-5">
                                <select class="form-control" name="product_id">
                                    @if(!$products->isEmpty())
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3"><button class="btn btn-info" type="submit">Ajouter une facture</button></div>
                        </div>
                        <input type="hidden" value="{{ $abonnement->id }}" name="abo_user_id">
                        <input type="hidden" value="facture" name="type">
                    </form>

                </div>
            </div>

        </div>
    </div>

@stop