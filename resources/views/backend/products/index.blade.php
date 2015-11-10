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
                    <table class="table" id="">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Image</th>
                                <th>Titre</th>
                                <th>Poids</th>
                                <th>Attributs</th>
                                <th>Catégories</th>
                                <th>Auteurs</th>
                                <th>Domaines</th>
                                <th>Prix</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$products->isEmpty())
                            @foreach($products as $product)
                                <tr>
                                    <td><a href="{{ url('admin/product/'.$product->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td><img style="height: 50px;" src="{{ asset('files/products/'.$product->image) }}" /></td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->weight }} grammes</td>
                                    <td>
                                        @if(!$product->attributes->isEmpty())
                                            @foreach($product->attributes as $attribute)
                                                <p>{{ $attribute->title }} :{{ $attribute->pivot->value }} </p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$product->categories->isEmpty())
                                            @foreach($product->categories as $categorie)
                                                <p>{{ $categorie->title }}</p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$product->authors->isEmpty())
                                            @foreach($product->authors as $author)
                                                <p>{{ $author->name }}</p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$product->domains->isEmpty())
                                            @foreach($product->domains as $domain)
                                                <p>{{ $domain->title }}</p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $product->price_cents }}</td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/product/'.$product->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $product->title }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@stop