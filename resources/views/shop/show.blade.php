@extends('layouts.shop')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Produit</h2>
        <a href="{{ url('shop')}}" class="btn btn-sm btn-default pull-right">Retour à la liste</a>
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
        {!! Form::open(array('url' => 'cart/addProduct')) !!}
            {!! Form::hidden('_token', csrf_token()) !!}
            <p>
                @if(!$product->domains->isEmpty())
                    @foreach($product->domains as $domain)
                        <span class="label label-info">{{ $domain->title }}</span>
                    @endforeach
                @endif
            </p>
            <h3>{{ $product->title }}</h3>

            <div>
                <p><strong>{{ $product->teaser }}</strong></p>
                {{ $product->description }}
                <h4>Auteurs</h4>
                <ul class="list-unstyled">
                    @if(!$product->authors->isEmpty())
                        @foreach($product->authors as $author)
                            <li><i>{{ $author->name }}</i></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="well">
                <ul class="list-unstyled">
                    @if(!$product->attributes->isEmpty())
                        @foreach($product->attributes as $attribute)
                            <li><strong>{{ $attribute->title }} :</strong>{{ $attribute->pivot->value }} </li>
                        @endforeach
                    @endif
                </ul>
                <ul class="list-unstyled">
                    @if(!$product->categories->isEmpty())
                        @foreach($product->categories as $categorie)
                            <li>{{ $categorie->title }}</li>
                        @endforeach
                    @endif
                </ul>

            </div>

            <br/>
            <p><strong>{{ $product->price_cents }} CHF</strong></p>
            <hr/>

            <input type="submit" value="Ajouter au panier" class="btn btn-sm btn-primary">

            {!! Form::hidden('product_id', $product->id) !!}
        {!! Form::close() !!}

    </div>

</div>
<!-- /.row -->
@endif

@stop