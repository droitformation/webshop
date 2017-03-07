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

                        <!-- Indicate it's from admin -->
                        <input type="hidden" name="admin" value="1">

                        <div id="adresseParent">
                            <a class="btn btn-primary accordion-toggle" data-toggle="adresseFind">Rechercher un utilisateur</a>
                            <a class="btn btn-info accordion-toggle" data-toggle="adresseMake">Ajouter une adresse</a>

                            <div class="collapse {{ !empty(old('type')) ? 'in' : '' }}" id="adresseFind" style="width: 400px;">

                                <!-- Autocomplete for adresse -->
                                <div class="autocomplete-wrapper">
                                    <?php $type = old('type'); ?>
                                    <?php $uid  = !empty($type) ? old(old('type')) : ''; ?>
                                    <div class="input-adresse" data-uid="{{ $uid }}" data-type="{{ old('type') }}" data-where="order"></div>
                                    <div class="choice-adresse"></div>
                                    <div class="adresse-find">
                                        <div class="form-group">
                                            <input id="search-adresse1" class="form-control search-adresse" placeholder="Chercher une adresse..." type="text">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Autocomplete for adresse -->

                            </div>
                            <div class="collapse {{ !empty(Session::get('adresse')) ? 'in' : '' }}" id="adresseMake">
                                <div class="row">
                                    @include('backend.orders.partials.adresse')
                                </div>
                            </div>
                        </div>

                        <div id="wrapper_clone_order">
                            @if(Session::has('old_products'))
                                <?php
                                    // wee want to repopulate the form with all the products and only assign an id to the first one
                                    // the others are just clones fileds with jquery
                                    $old_products  = Session::get('old_products');
                                    $first_product = array_shift($old_products);
                                ?>
                                @include('backend.orders.partials.product', ['id' => 'fieldset_clone_order', 'old_product' => $first_product, 'index' => ''])

                                @foreach($old_products as $index => $old_product)
                                    <?php $index = isset($index) ? $index + 1 : 1; ?>
                                    @include('backend.orders.partials.product', ['id' => '', 'old_product' => $old_product, 'index' => $index])
                                @endforeach
                            @else
                                @include('backend.orders.partials.product', ['id' => 'fieldset_clone_order', 'index' => ''])
                            @endif
                        </div>

                        <p><a href="#" class="btn btn-sm btn-default" id="cloneBtnOrder"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un produit</a></p>
                        <hr/>

                        <fieldset class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <i class="fa fa-truck"></i>&nbsp;&nbsp;Frais de port gratuit&nbsp;<input type="checkbox" {{ old('free') == 1 ? 'checked' : '' }} name="free" value="1">
                                        </label>
                                    </div>
                                    @if(!$shippings->isEmpty())
                                        <p>-- ou --</p>
                                        <div class="form-group">
                                            <select name="shipping_id" class="form-control">
                                                @foreach($shippings as $shipping)
                                                    <option value="{{ $shipping->id }}">{{ $shipping->title }} | {{ $shipping->price_cents }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label><i class="fa fa-dollar"></i>&nbsp; Modifier les taux de tva</label><br/>

                                <div class="form-group input-group">
                                    <input class="form-control" type="text" name="tva[taux_reduit]" value="{{ old('tva.taux_reduit') }}" placeholder="Réduit">
                                    <span class="input-group-addon">%</span>
                                </div><!-- /input-group -->
                                <div class="input-group">
                                    <input class="form-control" type="text" name="tva[taux_normal]" value="{{ old('tva.taux_normal') }}" placeholder="Normal">
                                    <span class="input-group-addon">%</span>
                                </div><!-- /input-group -->
                            </div>
                            <div class="col-md-6">
                                <label><i class="fa fa-info-circle"></i>&nbsp; Phrases d'informations</label><br/>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="background: #f1c40f; padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="message[warning]" value="{{ old('message.warning') }}" placeholder="Ajouter une phrase d'information">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="background: #85c744;padding: 2px;min-width: 15px;"></span>
                                    <input class="form-control" type="text" name="message[special]" value="{{ old('message.special') }}" placeholder="Information pour librairies">
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