@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <a href="{{ url('admin/abo/'.$abonnement->abo_id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
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
                                    <div class="collapse adresse-find">
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
                                <select class="form-control" name="type" id="typeSelect">
                                    <option {{ ($abonnement->renouvellement == 'auto' ?  'selected' : '') }} value="auto">Auto</option>
                                    <option {{ ($abonnement->renouvellement == 'year' ? 'selected' : '') }} value="year">1 an</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Statut</label>
                            <div class="col-sm-4 col-xs-8">
                                <select class="form-control" name="type" id="typeSelect">
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

                            <?php
                              /*  echo '<pre>';
                                print_r($groupes);
                                echo '</pre>';*/
                            ?>
                    @endif

                    <?php $abonnement->abo->load('products'); ?>

                    @if(isset($abonnement->abo->products))
                        <?php $products = $abonnement->abo->products->sortByDesc('created_at'); ?>
                        @foreach($products as $product)

                            <h5><i class="fa fa-star"></i>&nbsp;&nbsp;{{ $product->title }}</h5>

                            <div class="row">
                                <div class="col-md-6">
                                @if(isset($groupes[$product->id]))
                                    @foreach($groupes[$product->id] as $facture)
                                        <ul class="list-group">

                                            <?php $facture->load('rappels'); ?>

                                            @if($facture->payed_at)
                                                <li class="list-group-item list-group-item-success">
                                                <strong>Payement</strong> &nbsp; {!! $facture->payed_at->formatLocalized('%d %B %Y') !!}
                                                <a href="{{ url('admin/abonnement') }}" class="btn btn-danger btn-xs pull-right">&nbsp;x&nbsp;</a>
                                                </li>
                                            @else
                                                <li class="list-group-item">
                                                    <strong">En attente</strong>
                                                    <a data-toggle="collapse" href="#payInvoice_{{ $facture->id }}"  class="btn btn-success btn-xs pull-right">Payement</a>
                                                    <div class="collapse" id="payInvoice_{{ $facture->id }}">
                                                        <span class="clearfix"><p>&nbsp;</p></span>
                                                        <form action="{{ url('admin/abonnement') }}" method="POST">
                                                            {!! csrf_field() !!}
                                                            <div class="form-group input-group">
                                                                <input type="text" class="form-control datePicker" name="payed_at" placeholder="Payé le">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default" type="button">Ok</button>
                                                                </span>
                                                            </div><!-- /input-group -->
                                                        </form>
                                                    </div>
                                                </li>
                                            @endif

                                            @if(!$facture->rappels->isEmpty())
                                                @foreach($facture->rappels as $rappels)
                                                    <li class="list-group-item list-group-item-warning">
                                                        <strong>Rappel</strong> &nbsp;{{ $rappels->created_at->formatLocalized('%d %B %Y') }}
                                                        <a href="{{ url('admin/abonnement') }}" class="btn btn-danger btn-xs pull-right">&nbsp;x&nbsp;</a>
                                                    </li>
                                                @endforeach
                                            @endif

                                        </ul>
                                    @endforeach
                                @endif
                                </div>
                                <div class="col-md-6">



                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>

@stop