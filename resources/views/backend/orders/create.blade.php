@extends('backend.layouts.master')
@section('content')

    <div class="row"><!-- row -->
        <div class="col-md-12"><!-- col -->
            <p><a class="btn btn-default" href="{{ url('admin/orders') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des commandes</a></p>
        </div>
    </div>

    <!-- start row -->
    <div class="row">
        <div class="col-md-12 col-xs-12">

            <div class="panel panel-primary">
                <form id="formOrder" action="{{ url('admin/order') }}" class="validate-form" data-validate="parsley" method="POST">
                    {!! csrf_field() !!}
                    <div class="panel-body">
                        <h3>Créer une commande</h3>

                        <?php
                            $adresse = old('adresse');

                            unset($adresse['canton_id'],$adresse['pays_id'],$adresse['civilite_id']);

                            $adresse = (isset($adresse) ? array_filter(array_values($adresse)) : []);
                        ?>

                        <div id="adresseParent">
                            <a class="btn btn-primary accordion-toggle" data-toggle="adresseFind">Rechercher un utilisateur</a>
                            <a class="btn btn-info accordion-toggle" data-toggle="adresseMake">Ajouter une adresse</a>

                            <div class="collapse" id="adresseFind">
                                <div class="form-group">
                                    <input id="searchUser" class="form-control" placeholder="Chercher un utilisateur..." type="text">
                                </div>
                            </div>
                            <div class="collapse {{ !empty($adresse) ? 'in' : '' }}" id="adresseMake">
                                <div class="row">
                                    @include('backend.orders.partials.adresse')
                                </div>
                            </div>
                        </div>

                        <div id="inputUser" data-user="{{ old('user_id') }}"></div>
                        <div id="choiceUser"></div>

                        <div id="wrapper_clone_order">
                            @if(Session::has('old_products'))
                                <?php
                                    $old_products = Session::get('old_products');
                                    $first = array_shift($old_products);
                                ?>
                                @include('backend.orders.partials.product', ['id' => 'fieldset_clone_order', 'old_product' => $first])

                                @foreach($old_products as $old_product)
                                    @include('backend.orders.partials.product', ['id' => '', 'old_product' => $old_product])
                                @endforeach
                            @else
                                @include('backend.orders.partials.product', ['id' => 'fieldset_clone_order'])
                            @endif
                        </div>

                        <p><a href="#" class="btn btn-sm btn-default" id="cloneBtnOrder"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un produit</a></p>
                        <hr/>

                        <fieldset class="row">
                            <div class="form-group col-md-2">
                                <label><i class="fa fa-truck"></i>&nbsp;&nbsp;Frais de port</label><br/>
                                <div class="checkbox">
                                    <label>
                                         Gratuit&nbsp;<input type="checkbox" {{ old('free') == 1 ? 'checked' : '' }} name="free" value="1">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label><i class="fa fa-dollar"></i>&nbsp; Modifier les taux de tva</label><br/>

                                <div class="form-group input-group">
                                    <input class="form-control" type="text" name="tva[taux_reduit]" value="{{ old('tva[taux_reduit]') }}" placeholder="Réduit">
                                    <span class="input-group-addon">%</span>
                                </div><!-- /input-group -->
                                <div class="input-group">
                                    <input class="form-control" type="text" name="tva[taux_normal]" value="{{ old('tva[taux_normal]') }}" placeholder="Normal">
                                    <span class="input-group-addon">%</span>
                                </div><!-- /input-group -->
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-6">
                                <label><i class="fa fa-info-circle"></i>&nbsp; Phrases d'informations</label><br/>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="background: #f1c40f; padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="message[warning]" value="{{ old('message[warning]') }}" placeholder="Ajouter une phrase d'information">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="background: #85c744;padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="message[special]" value="{{ old('message[special]') }}" placeholder="Information pour librairies">
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