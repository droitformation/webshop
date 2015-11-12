@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-right" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/product/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Produits <span class="muted">livres</span></h4>
                </div>
                <div class="panel-body">

                        @if(!$products->isEmpty())
                        @foreach($products as $product)

                            <div class="card">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>
                                            <img style="height: 80px; float:left; margin-right: 10px;" src="{{ asset('files/products/'.$product->image) }}" />
                                            {{ $product->title }}
                                            <span class="clearfix"></span>
                                        </p>
                                        @if($product->orders->count() == 0)
                                            <form action="{{ url('admin/product/'.$product->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-what="Supprimer" data-action="{{ $product->title }}" class="btn btn-danger btn-xs deleteAction">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-unstyled">
                                            @if(!$product->attributes->isEmpty())
                                                @foreach($product->attributes as $attribute)
                                                    <li><strong>{{ $attribute->title }}</strong><br/>{{ $attribute->pivot->value }} </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-1">
                                        <ul class="list-unstyled">
                                            @if(!$product->categories->isEmpty())
                                                @foreach($product->categories as $categorie)
                                                    <li>{{ $categorie->title }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-1">
                                        <ul class="list-unstyled">
                                            @if(!$product->authors->isEmpty())
                                                @foreach($product->authors as $author)
                                                    <li>{{ $author->name }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-1">
                                        <ul class="list-unstyled">
                                            @if(!$product->domains->isEmpty())
                                                @foreach($product->domains as $domain)
                                                    <li>{{ $domain->title }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-1">
                                        <p>{{ $product->price_cents }} CHF</p>
                                    </div>
                                    <div class="col-md-1">
                                        <p> {{ $product->weight }} grammes</p>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <a href="{{ url('admin/product/'.$product->id) }}" class="btn btn-sm btn-info">éditer</a>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        @endif

                </div>
            </div>

        </div>
    </div>

@stop