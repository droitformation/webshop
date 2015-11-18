@extends('backend.layouts.master')
@section('content')

    <div class="row"><!-- row -->
        <div class="col-md-12"><!-- col -->
            <p><a class="btn btn-default" href="{{ url('admin/orders') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des commandes</a></p>
        </div>
    </div>

    <!-- start row -->
    <div class="row">
        <div class="col-md-11">

            <div class="panel panel-primary">
                <form id="formOrder" action="{{ url('admin/order') }}" class="validate-form" data-validate="parsley" method="POST">
                    {!! csrf_field() !!}
                    <div class="panel-body">
                        <h3>Créer une commande</h3>

                            <div id="adresseParent">
                                <a class="btn btn-primary accordion-toggle" data-toggle="adresseFind">Rechercher un utilisateur</a>
                                <a class="btn btn-info accordion-toggle" data-toggle="adresseMake">Ajouter une adresse</a>

                                <div class="collapse" id="adresseFind">
                                    <div class="form-group">
                                        <input id="searchUser" class="form-control" placeholder="Chercher un utilisateur..." type="text">
                                    </div>
                                </div>
                                <div class="collapse" id="adresseMake">
                                    <div class="row">
                                        @include('backend.orders.partials.adresse')
                                    </div>
                                </div>
                            </div>

                            <div id="inputUser"></div>
                            <div id="choiceUser"></div>

                            <div id="wrapper_clone_order">
                                <fieldset class="field_clone_order" id="fieldset_clone_order">
                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-lg-7 col-md-6 col-xs-12">
                                                <label>Produit</label>
                                                <select name="order[products][]" required class="chosen-select form-control" data-placeholder="produits">
                                                    <option value="">Choix</option>
                                                    @if(!$products->isEmpty())
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-lg-1 col-md-2 col-xs-12">
                                                <label>Quantité</label>
                                                <input class="form-control" name="order[qty][]">
                                            </div>
                                            <div class="col-lg-1 col-md-2 col-xs-12">
                                                <label>Rabais</label>
                                                <div class="input-group">
                                                    <input class="form-control" name="order[rabais][]">
                                                    <span class="input-group-addon">%</span>
                                                </div><!-- /input-group -->
                                            </div>
                                            <div class="col-lg-2 col-md-1 col-xs-12">
                                                <label></label>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="order[gratuit][]" value="1"> Livre gratuit</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-xs-12 text-right">
                                                <label>&nbsp;</label>
                                                <p><a href="#" class="btn btn-danger btn-sm remove_order">x</a></p>
                                            </div>
                                        </div>

                                    </div>
                                </fieldset>
                            </div>
                            <p><a href="#" class="btn btn-sm btn-default" id="cloneBtnOrder"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un produit</a></p>
                            <hr/>
                            <fieldset>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            &nbsp; <i class="fa fa-truck"></i>&nbsp; Frais de port gratuit &nbsp;<input type="checkbox" name="order[free]" value="1">
                                        </label>
                                    </div>
                                </div>
                                <label>Modifier les taux de tva</label><br/>
                                <div class="form-group row">
                                    <div class="col-md-2 col-xs-3">
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="order[tva][taux_reduit]" value="" placeholder="Réduit">
                                            <span class="input-group-addon">%</span>
                                        </div><!-- /input-group -->
                                    </div>
                                    <div class="col-md-2 col-xs-3">
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="order[tva][taux_normal]" value="" placeholder="Normal">
                                            <span class="input-group-addon">%</span>
                                        </div><!-- /input-group -->
                                    </div>
                                </div>
                            </fieldset>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-info">Valider la commande</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop