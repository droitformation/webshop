@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/products') }}" method="post">
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-md-2">
                                @include('backend.products.partials.sort',['title' => 'Catégories', 'items' => $categories, 'types' => 'categories', 'type' => 'categorie_id'])
                            </div>
                            <div class="col-md-2">
                                @include('backend.products.partials.sort',['title' => 'Auteurs',    'items' => $authors, 'types' => 'authors', 'type' => 'author_id'])
                            </div>
                            <div class="col-md-2">
                                @include('backend.products.partials.sort',['title' => 'Domaines',   'items' => $domains,  'types' => 'domains', 'type' => 'domain_id'])
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br/>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i> &nbsp;Trier</button>
                                <a class="btn btn-default" href="{{ url('admin/products') }}">Tous</a>
                            </div>
                            <div class="col-md-4 text-right">
                                <label>&nbsp;</label><br/>
                                <a href="{{ url('admin/product/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Livres</h4>
                </div>
                <div class="panel-body" id="search-list">

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3 col-md-push-6">
                             <input class="form-control fuzzy-search" placeholder="Recherche par mots clés" />
                        </div>
                    </div>

                    <div class="list">
                        @if(!$products->isEmpty())
                        @foreach($products as $product)

                            <div class="card">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>
                                            <a href="{{ url('admin/product/'.$product->id) }}">
                                                <img style="height: 50px; float:left; margin-right: 10px;" src="{{ asset('files/products/'.$product->image) }}" />
                                                <span class="title">{{ $product->title }}</span>
                                                @if(!$product->attributs->isEmpty())
                                                    @foreach($product->attributs as $attribute)
                                                        <span class="{{ $attribute->title }} text-hide" style="height: 0;">{{ $attribute->pivot->value }}</span>
                                                    @endforeach
                                                @endif
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-1">
                                        <ul class="list-unstyled">
                                            @if(!$product->categories->isEmpty())
                                                @foreach($product->categories as $categorie)
                                                    <li class="categorie">{{ $categorie->title }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-unstyled" style="display: none;">
                                            @if(!$product->authors->isEmpty())
                                                @foreach($product->authors as $author)
                                                    <li class="author">{{ $author->name }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <ul class="list-unstyled" style="display: none;">
                                            @if(!$product->domains->isEmpty())
                                                @foreach($product->domains as $domain)
                                                    <li class="domain">{{ $domain->title }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-1">
                                        <p><strong>Prix:</strong> {{ $product->price_cents }} CHF</p>
                                        <p><strong>Poids:</strong> {{ $product->weight }} gr.</p>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <form action="{{ url('admin/product/'.$product->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <a href="{{ url('admin/product/'.$product->id) }}" class="btn btn-xs btn-info">&nbsp;éditer&nbsp;</a>
                                            @if($product->orders->count() == 0)
                                            <button data-what="Supprimer" data-action="{{ $product->title }}" class="btn btn-danger btn-sm deleteAction">&nbsp; x &nbsp;</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        @endif
                    </div>

                    <ul class="pagination"></ul>

                </div>
            </div>

        </div>
    </div>

@stop