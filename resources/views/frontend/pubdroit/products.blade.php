@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

            <div class="heading-bar">
                <h2>{{ $title }} <strong>{{ $label }}</strong></h2>
                <span class="h-line"></span>
            </div>

            <section class="list-holder">

                @if(!$products->isEmpty())
                    @foreach($products as $product)

                        <article class="item-holder">
                            <div class="col-md-2">
                                <a href="{{ url('product/'.$product->id) }}"><img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}" /></a>
                            </div>
                            <div class="col-md-10">
                                <div class="title-bar">
                                    <a href="{{ url('product/'.$product->id) }}">{{ $product->title }}</a>
                                    <span>{!! $product->teaser !!}</span>
                                </div>
                                @if(!$product->authors->isEmpty())
                                    <div class="author-list">
                                        <h4>{{ $product->authors->count() > 1 ? 'Auteurs' : 'Auteur' }}</h4>
                                        <ul class="the-icons clearfix">
                                        @foreach($product->authors as $author)
                                           <li><i class="fa fa-user"></i> &nbsp;{{ $author->name }}</li>
                                        @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="readmore product-description">
                                    <h4>Description</h4>
                                    {!! $product->description !!}
                                </div>
                                <div class="b-post-bottom">
                                    <ul class="post-nav">
                                        <?php $attributs = $product->attributs->filter(function ($value, $key) {return in_array($value->id, [1,2]); }); ?>

                                        @if(!$attributs->isEmpty())
                                            @foreach($attributs as $attribute)
                                                <li><strong>{{ $attribute->title }}</strong> {{ $attribute->pivot->value }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="cart-price pull-right">
                                        <form method="post" action="{{ url('cart/addProduct') }}" class="form-inline">{!! csrf_field() !!}
                                            <button type="submit" class="cart-btn2">Ajouter au panier</button>
                                            <span class="price">{{ $product->price_cents }} CHF</span>
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </article>

                    @endforeach
                @endif

            </section>


        </div>
    </section>

@stop
