@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <a href="{{ url('admin/abonnements/'.$abonnement->abo_id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-midnightblue">

                <form action="{{ url('admin/abonnement/'.$abonnement->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Abonnement</h4>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Numéro</label>
                            <div class="col-sm-4 col-xs-5">
                                <input type="text" class="form-control" value="{{ $abonnement->numero }}" name="numero">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label">Adresse</label>
                            <div class="col-sm-8 col-xs-12">
                                <!-- Autocomplete for tiers adresse -->
                                @include('backend.partials.search-adresse', ['adresse_id' => $abonnement->adresse_id, 'type' => 'adresse_id'])
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tiers payant</label>
                            <div class="col-sm-4 col-xs-5">
                                <!-- Autocomplete for tiers adresse -->
                                @include('backend.partials.search-adresse', ['adresse_id' => $abonnement->tiers_id, 'type' => 'tiers_id'])
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix spécial</label>
                            <div class="col-sm-4 col-xs-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $abonnement->price_cents }}" name="price"><span class="input-group-addon">CHF</span>
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

            @if( $abonnement->status == 'abonne')

                <div class="panel panel-midnightblue">
                    <div class="panel-heading">
                        <h4><i class="fa fa-star-half-empty"></i> &nbsp;Payements</h4>
                    </div>
                    <div class="panel-body">

                        @if(isset($abonnement->factures))
                            <?php $groupes = $abonnement->factures->groupBy('product_id'); ?>
                        @endif

                        @if(isset($abonnement->abo->products))
                            <?php $products = $abonnement->abo->products->sortByDesc('created_at'); ?>
                            @foreach($products as $product)

                                <!-- Start factures for product-->
                                <div class="well well-sm">
                                    <h4><i class="fa fa-arrow-circle-right"></i>&nbsp;{{ $product->title }} {{ $product->reference }} {{ $product->edition }}</h4>
                                    @if(isset($groupes[$product->id]))
                                        @foreach($groupes[$product->id] as $facture)
                                            @include('backend.abonnements.partials.facture', ['facture' => $facture])
                                        @endforeach

                                        @if(!$facture->rappels->isEmpty())
                                            <hr/>
                                            @include('backend.abonnements.partials.rappels', ['rappels' => $facture->rappels])
                                        @endif
                                    @endif
                                </div>
                                <!-- End factures for product-->

                            @endforeach
                        @endif

                        <h4>Créer une facture</h4>

                        <!-- Create facture form -->
                        <form action="{{ url('admin/facture') }}" method="POST">{!! csrf_field() !!}
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
                                    <input type="hidden" value="{{ $abonnement->id }}" name="abo_user_id">
                                    <input type="hidden" value="facture" name="type">
                                </div>
                                <div class="col-md-3"><button class="btn btn-info" type="submit">Ajouter une facture</button></div>
                            </div>
                        </form>
                        <!-- End create facture form -->

                    </div>
                </div>
            @endif
        </div>
    </div>

@stop