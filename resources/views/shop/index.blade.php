@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Shop</h2>
    </div>
</div>

<!-- Page Features -->

@if(!$products->isEmpty())

   <?php $chunks = $products->chunk(4); ?>

    @foreach($chunks  as $chunk)
        <div class="row text-center">
            @foreach($chunk  as $product)
                <div class="col-md-3 col-sm-6 hero-feature">
                    <div class="thumbnail">
                        <img src="{{ asset('files/products/'.$product->image) }}" alt="">
                        <div class="caption">
                            <h5>{{ $product->title }}</h5>
                            <p>
                                <a href="#" class="btn btn-sm btn-primary">Buy Now!</a>
                                <a href="{{ url('product/'.$product->id) }}" class="btn btn-sm btn-default">Plus d'info</a></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endif

<!-- /.row -->

@stop