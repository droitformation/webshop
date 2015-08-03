@extends('layouts.shop')
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
                                {!! Form::open(array('url' => 'cart/addProduct')) !!}
                                {!! Form::hidden('_token', csrf_token()) !!}
                                <button type="submit" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-shopping-cart"></i></button>
                                <a href="{{ url('shop/product/'.$product->id) }}" class="btn btn-sm btn-default">Plus d'info</a>
                                {!! Form::hidden('product_id', $product->id) !!}
                                {!! Form::close() !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endif

<!-- /.row -->

@stop