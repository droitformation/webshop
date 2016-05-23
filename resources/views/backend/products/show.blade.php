@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <p><a href="{{ url('admin/product') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
        <div class="col-md-6 text-right">
            <form action="{{ url('admin/product/'.$product->id) }}" method="POST" class="form-horizontal">
                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                @if($product->orders->count() == 0)
                    <button data-what="Supprimer" data-action="{{ $product->title }}" class="btn btn-danger deleteAction">Supprimer</button>
                @endif
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/product/'.$product->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">
                        <h3>&Eacute;diter le livre</h3>
                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Visibilité</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input {{ !$product->hidden ? 'checked' : '' }} type="radio" name="hidden" value="0"> Visible
                                </label>
                                <label class="radio-inline">
                                    <input {{ $product->hidden ? 'checked' : '' }} type="radio" name="hidden" value="1"> Caché
                                </label>
                            </div>
                        </div>

                        @if(!empty($product->image ))
                            <div class="form-group">
                                <label for="file" class="col-sm-3 control-label">Image</label>
                                <div class="col-sm-2">
                                    <img style="height: 160px;;" class="thumbnail" src="{{ asset('files/products/'.$product->image) }}" alt="" />
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Changer l'image</label>
                            <div class="col-sm-8">
                                {!! Form::file('file') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-8 col-xs-8">
                                <input type="text" class="form-control" value="{{ $product->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Teaser</label>
                            <div class="col-sm-8">
                                <textarea style="height: 100px;" class="form-control" name="teaser">{{ $product->teaser }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control redactor">{{ $product->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group well well-small">
                            <label class="col-sm-3 control-label">Lien de commande externe</label>
                            <div class="col-sm-7 col-xs-6">
                                <input type="text" class="form-control" name="url" value="{{ $product->url }}" placeholder="http://">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Poids maximum</label>
                            <div class="col-sm-4 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $product->weight }}" name="weight">
                                    <span class="input-group-addon">grammes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-4 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $product->price_cents }}" name="price">
                                    <span class="input-group-addon">CHF</span>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Stock</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" disabled name="sku" value="{{ $product->sku }}">
                                    <span class="input-group-addon">livres</span>
                                </div>
                            </div>
                            <div class="col-sm-5 text-right">
                                <!-- Button trigger modals -->
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#stockModal">Changer le stock</button>
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#stockHistory"><i class="fa fa-history"></i></button>
                            </div>
                        </div>

                        <hr/>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Existe sous forme d'abonnement</label>
                            <div class="col-sm-4 col-xs-8">
                                <?php $product_abos = $product->abos->lists('id')->all(); ?>
                                @if(!$abos->isEmpty())
                                    <select name="abo_id[]" class="form-control" multiple>
                                        <option value="">Choix</option>
                                        @foreach($abos as $abo)
                                            <option {{ in_array($abo->id ,$product_abos) ? 'selected' : '' }} value="{{ $abo->id }}">{{ $abo->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <p class="text-danger"><i class="fa fa-exclamation-triangle"></i> &nbsp; Attention indiquer la référence et l'édition dans les attributs pour la facture !</p>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer mini-footer">
                        <div class="col-sm-2">
                            <input type="hidden" value="{{ $product->id }}" name="id">
                        </div>
                        <div class="col-sm-9 text-right">
                            <button class="btn btn-primary" type="submit">Envoyer </button>
                        </div>
                    </div>
                </form>

                <!-- stock change modal outside of product form -->
                @include('backend.stocks.partials.product')

                <!-- stock history modal outside of product form -->
                @include('backend.stocks.partials.history', ['stocks' => $product->stocks])

            </div>
        </div>
        <div class="col-md-5">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-star"></i> &nbsp;Attributs</h4>
                </div>
                <div class="panel-body">

                    @if(!$product->attributs->isEmpty())
                        @foreach($product->attributs as $attribute)
                            <div>
                                <strong>{{ $attribute->title }}</strong><br/>{{ $attribute->pivot->value }}
                                <form action="{{ url('admin/product/removeAttribut/'.$product->id) }}" method="POST" class="pull-right">{!! csrf_field() !!}
                                    <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
                                    <button data-action="{{ $attribute->title }}" class="btn btn-danger btn-xs deleteAction">x</button>
                                </form>
                            </div>
                        @endforeach
                        <hr/>
                    @endif

                    <h4>Ajouter un attribut</h4>

                    <form action="{{ url('admin/product/addAttribut/'.$product->id) }}" method="POST">{!! csrf_field() !!}
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Type</label>
                                @if(!$attributes->isEmpty())
                                    <select class="form-control" name="attribute_id">
                                        <option value="">Choix</option>
                                        @foreach($attributes as $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label>Valeur</label>
                                <div class="input-group">
                                    <input type="text" name="value" class="form-control" placeholder="Valeur">
                                    <span class="input-group-btn"><button class="btn btn-info" type="submit">Ajouter</button></span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            @include('backend.products.partials.item',['title' => 'Thème', 'items' => $categories, 'types' => 'categories'])
            @include('backend.products.partials.item',['title' => 'Auteur',    'items' => $authors,    'types' => 'authors'])
            @include('backend.products.partials.item',['title' => 'Domaine',   'items' => $domains,    'types' => 'domains'])

        </div>
    </div>

@stop