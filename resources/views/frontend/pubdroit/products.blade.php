@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p><a href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

            <div class="heading-bar">
                <h2>{{ $title }} <strong>{{ $label }}</strong></h2>
                <span class="h-line"></span>
            </div>

            <section class="list-holder">

                @if(!$products->isEmpty())
                    @foreach($products as $product)

                        <article class="item-holder">
                            <div class="col-md-2">
                                <a href="{{ url('pubdroit/product/'.$product->id) }}">
                                    <img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}" />
                                </a>
                            </div>
                            <div class="col-md-10">
                                <div class="title-bar">
                                    <a href="{{ url('pubdroit/product/'.$product->id) }}">{{ $product->title }}</a>
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
                                    <div class="pull-right">
                                        <!-- Product put in the basket button -->
                                        @include('frontend.pubdroit.partials.basket')
                                        <!-- END Product put in the basket button -->
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
