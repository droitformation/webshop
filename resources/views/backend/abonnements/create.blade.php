@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/abonnements/'.$abo->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/abonnement') }}" method="POST" class="form-horizontal">
                    {!! csrf_field() !!}

                    <?php $numero = $abo->abonnements->max('numero') + 1; ?>

                    <div class="panel-body" id="appComponent">
                        <h4>Ajouter un abonné</h4>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Numéro</label>
                            <div class="col-sm-4 col-xs-5">
                                <input type="text" class="form-control" value="{{ $numero }}" name="numero">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Compte</label>
                            <div class="col-sm-7 col-xs-5">
                                <list-autocomplete type="user_id" chosen_id=""></list-autocomplete>

                                <p class="mt-10 text-danger">On peut gérer le tiers payant une fois l'abonnement crée</p>
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
                                <input type="text" class="form-control" value="1" name="exemplaires">
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
                                    <option value="tiers">Abonné Tiers payant</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Réference</label>
                            <div class="col-sm-7 col-xs-8">
                                <input type="text" class="form-control" value="" name="reference">
                            </div>
                        </div>

                        <!-- New version -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Réferences</label>
                            <div class="col-sm-8 col-xs-8">
                                <div class="input-group">
                                    <span class="input-group-addon">N° référence</span>
                                    <input type="text" class="form-control" value="{{ old('reference_no') }}" name="reference_no">
                                </div><br>

                                <div class="input-group">
                                    <span class="input-group-addon">N° commande</span>
                                    <input type="text" class="form-control" value="{{ old('transaction_no') }}" name="transaction_no">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Remarque</label>
                            <div class="col-sm-7 col-xs-12">
                                <textarea class="form-control redactorSimple" name="remarque"></textarea>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $abo->id }}" name="abo_id">
                        <input type="hidden" value="{{ $abo->current_product->id }}" name="product_id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" id="createAbo" class="btn btn-info">Créer un abo</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop