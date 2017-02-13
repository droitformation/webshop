@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/abo/'.$abo->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">
                        <h3>&Eacute;diter abo</h3>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-4 col-xs-6">
                                <input type="text" class="form-control" value="{{ $abo->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="{{ $abo->price_cents }}" name="price">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Frais de port</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="{{ $abo->shipping_cents }}" name="shipping">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Centre/institut</label>
                            <div class="col-sm-4 col-xs-6">
                                <input type="text" class="form-control" name="name" value="{{ $abo->name }}">
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">facultatif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Compte pour BV</label>
                            <div class="col-sm-4 col-xs-6">
                                <input type="text" class="form-control" name="compte" value="{{ $abo->compte }}" placeholder="facultatif">
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Par défaut le compte est celui indiqué dans la configuration des abos</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Adresse pour BV</label>
                            <div class="col-sm-4 col-xs-6">
                                <textarea class="form-control redactorSimple" name="adresse">{{ \Registry::get('abo.infos.adresse') }}</textarea>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Par défaut l'adresse est celle indiquée dans la configuration des abos</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Remarques</label>
                            <div class="col-sm-4 col-xs-6">
                                <textarea class="form-control redactorSimple" name="remarque">{!! $abo->remarque !!}</textarea>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Indiqué dans le shop (panier)</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Récurrence</label>
                            <div class="col-sm-4 col-xs-6">
                                <select class="form-control" name="plan">
                                    <option value=""></option>
                                    @foreach($plans as $name => $plan)
                                        <option {{ $name ==  $abo->plan ? 'selected' : '' }} value="{{ $name }}">{{ $plan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(!empty($abo->logo ))
                            <div class="form-group">
                                <label for="file" class="col-sm-3 control-label">Fichier</label>
                                <div class="col-sm-4">
                                    <div class="list-group">
                                        <div class="list-group-item text-center">
                                            <a href="#"><img height="120" src="{!! asset('files/main/'.$abo->logo) !!}" alt="logo" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Changer le logo</label>
                            <div class="col-sm-7">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        {!! Form::file('file') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 col-xs-12 control-label">Produits</label>
                            <div class="col-sm-5 col-xs-12">
                                <select multiple class="form-control" id="multi-select" name="products_id[]">
                                    <?php $product_abos = !$abo->products->isEmpty() ? $abo->products->pluck('id')->all() : []; ?>
                                    @if(!$products->isEmpty())
                                        @foreach($products as $product)
                                            <option {{ in_array($product->id,$product_abos) ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Seul les livres ayant les éléments de références pour la facture sont affichés</p>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <input name="id" value="{{ $abo->id }}" type="hidden">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop