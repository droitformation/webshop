@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Produit</h2>
        <a href="{{ url('/')}}" class="btn btn-sm btn-default pull-right">Retour à la liste</a>
    </div>
</div>

@if($product)
<!-- Page Features -->
<div class="row">

    <div class="col-md-5 col-sm-12">
        <div class="thumbnail">
            <img src="{{ asset('files/products/'.$product->image) }}" alt="">
        </div>
    </div>
    <div class="col-md-7 col-sm-12">
        {!! Form::open(array('url' => 'addProduct')) !!}
            {!! Form::hidden('_token', csrf_token()) !!}
            <h3>{{ $product->title }}</h3>
            <p><strong>{{ $product->teaser }}</strong></p>
            <div>{{ $product->description }}</div>
            <hr/>

            <input type="submit" value="Ajouter au panier" class="btn btn-sm btn-primary">

            {!! Form::hidden('product_id', $product->id) !!}
        {!! Form::close() !!}

    </div>

</div>
<!-- /.row -->
@endif

@stop