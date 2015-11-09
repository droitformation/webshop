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
                                <th>Titre</th>
                                <th>Poids maximum</th>
                                <th>Prix</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$products->isEmpty())
                            @foreach($products as $product)
                                <tr>
                                    <td><a href="{{ url('admin/product/'.$product->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->weight }} grammes</td>
                                    <td>{{ $product->price_cents }}</td>
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