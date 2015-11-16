@extends('backend.layouts.master')
@section('content')

    <div class="row"><!-- row -->
        <div class="col-md-12"><!-- col -->
            <p><a class="btn btn-default" href="{{ url('admin/orders') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des commandes</a></p>
        </div>
    </div>

    <!-- start row -->
    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-magenta">
                <div class="panel-body">
                    <h3>Créer une commande</h3>

                    <form id="formOrder" class="validate-form" data-validate="parsley">
                        <div class="form-group">
                            <label><strong>Rechercher un utilisateur</strong></label>
                            <input id="searchUser" class="form-control" placeholder="Chercher un utilisateur..." type="text">
                        </div>

                        <div id="inputUser"></div>

                        <div id="wrapper_clone_order">
                            <fieldset class="field_clone_order" id="fieldset_clone_order">
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label>Produit</label>
                                            <select name="products[]" required class="chosen-select form-control" data-placeholder="produits">
                                                <option value="">Choix</option>
                                                @if(!$products->isEmpty())
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Quantité</label>
                                            <input class="form-control" name="qty[]">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Livre gratuit</label>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="gratuit[]" value="1"> Oui</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        </div>
                        <hr/>
                        <p><a href="#" class="btn btn-sm btn-info pull-right" id="cloneBtnOrder"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un produit</a></p>

                    </form>

                </div>
            </div>

        </div>
    </div>
    <!-- start row -->
    <div class="row">
        <div class="col-md-7">
            <!-- Panel -->
            <div class="panel panel-info">
                <div class="panel-body">
                    <div id="choiceUser"></div>
                    <div id="choiceProducts"></div>
                    <button type="submit" class="btn btn-info">Valider cette commander</button>
                </div>
            </div>
            <!-- END panel -->

        </div>
    </div>

@stop