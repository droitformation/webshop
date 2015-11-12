@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/product') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Editer le produit</h4>
                </div>
                <form action="{{ url('admin/product/'.$product->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        @if(!empty($product->image ))
                            <div class="form-group">
                                <label for="file" class="col-sm-2 control-label">Image</label>
                                <div class="col-sm-2">
                                    <img style="height: 160px;;" class="thumbnail" src="{{ asset('files/products/'.$product->image) }}" alt="" />
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="file" class="col-sm-2 control-label">Changer l'image</label>
                            <div class="col-sm-8">
                                {!! Form::file('file') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Titre</label>
                            <div class="col-sm-8 col-xs-8">
                                <input type="text" class="form-control" value="{{ $product->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Teaser</label>
                            <div class="col-sm-8">
                                <textarea style="height: 100px;" class="form-control" name="teaser">{{ $product->teaser }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control redactor">{{ $product->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Poids maximum</label>
                            <div class="col-sm-4 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $product->weight }}" name="weight">
                                    <span class="input-group-addon">grammes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Prix</label>
                            <div class="col-sm-4 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $product->price_cents }}" name="price">
                                    <span class="input-group-addon">CHF</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Stock</label>
                            <div class="col-sm-4 col-xs-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                                    <span class="input-group-addon">livres</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer mini-footer">
                        <div class="col-sm-2">
                            <input type="hidden" value="{{ $product->id }}" name="id">
                        </div>
                        <div class="col-sm-7">
                            <button class="btn btn-primary" type="submit">Envoyer </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-star"></i> &nbsp;Attributs</h4>
                </div>
                <div class="panel-body">

                    @if(!$product->attributes->isEmpty())
                        @foreach($product->attributes as $attribute)
                            <div>
                                <strong>{{ $attribute->title }}</strong><br/>{{ $attribute->pivot->value }}
                                <form action="{{ url('admin/product/removeAttribut/'.$product->id) }}" method="POST" class="pull-right">{!! csrf_field() !!}
                                    <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
                                    <button data-action="{{ $attribute->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                </form>
                            </div>
                        @endforeach
                        <hr/>
                    @endif

                    <h4>Ajouter un attribut</h4>
                    <form action="{{ url('admin/product/attributs/'.$product->id) }}" method="POST">
                        {!! csrf_field() !!}
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
                                    <span class="input-group-btn">
                                        <button class="btn btn-info" type="submit">Ajouter</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                     
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop