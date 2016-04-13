@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter un abonné</h4>
                </div>
                <form action="{{ url('admin/abonnement') }}" method="POST" class="form-horizontal">
                    {!! csrf_field() !!}

                    <?php $numero = $abo->abonnements->max('numero') + 1; ?>

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Numéro</label>
                            <div class="col-sm-4 col-xs-5">
                                <input type="text" class="form-control" value="{{ $numero }}" name="numero">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label">Adresse</label>
                            <div class="col-sm-4 col-xs-12">

                                <!-- Autocomplete for adresse -->
                                <div class="autocomplete-wrapper">
                                    <div class="input-adresse" data-adresse="{{ old('adresse_id') }}" data-type="adresse_id"></div>
                                    <div class="choice-adresse"></div>
                                    <div class="collapse in adresse-find adresse-find-abo">
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
                                    <div class="input-adresse" data-adresse="{{ old('tiers_id') }}" data-type="tiers_id"></div>
                                    <div class="choice-adresse"></div>
                                    <div class="collapse in adresse-find adresse-find-abo">
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
                                    <input type="text" class="form-control" value="" name="price">
                                    <span class="input-group-addon">CHF</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Exemplaires</label>
                            <div class="col-sm-4 col-xs-5">
                                <input type="text" class="form-control" value="" name="exemplaires">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Renouvellement</label>
                            <div class="col-sm-4 col-xs-8">
                                <select class="form-control" name="renouvellement">
                                    <option value="auto">Auto</option>
                                    <option value="year">1 an</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Statut</label>
                            <div class="col-sm-4 col-xs-8">
                                <select class="form-control" name="status">
                                    <option value="abonne">Abonné</option>
                                    <option value="gratuit">Gratuit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Réference</label>
                            <div class="col-sm-8 col-xs-8">
                                <input type="text" class="form-control" value="" name="reference">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Remarque</label>
                            <div class="col-sm-8 col-xs-12">
                                <textarea class="form-control redactorSimple" name="remarque"></textarea>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $abo->id }}" name="abo_id">
                        <input type="hidden" value="{{ $abo->current_product['id'] }}" name="product_id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer un abo</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop