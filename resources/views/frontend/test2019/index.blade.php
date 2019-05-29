@extends('frontend.pubdroit.layouts.master2019')
@section('content')

    <h1 class="main-title main-title-border maint-title-link">
        <span><i class="fa fa-calendar"></i>&nbsp; Prochains évenements</span>
        <a href="{{ url('') }}">Voir plus &nbsp;<i class="fas fa-caret-right"></i></a>
    </h1>

    <section class="list-cards">
        @if(!$colloques->isEmpty())
            @foreach($colloques as $colloque)
                <article class="colloque-card">
                    <a class="colloque-card-thumb" href="{{ $colloque->register_url }}">
                        <img src="{{ secure_asset($colloque->frontend_illustration) }}" alt='{{ $colloque->titre }}'/>
                    </a>
                    <div class="colloque-card-infos">
                        <h3><a href="{{ $colloque->register_url }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                        <div class="colloque-card-content">
                            <p>{{ $colloque->sujet }}</p>
                        </div>
                        <p><i class="fa fa-calendar"></i>&nbsp; {{ $colloque->event_date }}</p>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <h1 class="main-title main-title-border maint-title-link">
        <span><i class="fa fa-book"></i>&nbsp; Dernières publications</span>
        <a href="{{ url('') }}">Voir plus &nbsp;<i class="fas fa-caret-right"></i></a>
    </h1>

    <section class="list-cards list-cards-books">
        @if(isset($products) && !$products->isEmpty())
            @foreach($products as $product)
                <article class="book-card">
                    <a class="book-card-thumb" href="{{ url('pubdroit/product/'.$product->id) }}">
                        <img src="{{ secure_asset('files/products/'.$product->image) }}" alt="{{ $product->title }}" />
                    </a>
                    <div class="book-card-infos">
                        <h3><a href="{{ url('pubdroit/product/'.$product->id) }}">{{ $product->title }}</a></h3>
                        <div class="book-card-content">
                            @if(!$product->authors->isEmpty())
                                @foreach($product->authors as $author)
                                    <p>{{ $author->name }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        @endif
    </section>


@stop
