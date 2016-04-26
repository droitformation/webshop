@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">
            <div class="m-bottom wellcome-msg">
                <h2>BIENVENUE sur publications-droit.ch.</h2>
                <p>Portail des divers publications de la Faculté de droit de l'Université de Neuchâtel</p>
            </div>
        </div>
    </section>
    
    <section class="row">
        <section class="col-md-8 col-xs-12">

            <div class="heading-bar">
                <h2><i class="fa fa-calendar"></i> &nbsp;Prochains Événements</h2>
                <span class="h-line"></span>
            </div>

            @if(!$colloques->isEmpty())
                <?php $chunks = $colloques->chunk(1); ?>
                @foreach($chunks as $chunk)
                    <section class="row">
                        @foreach($chunk as $colloque)

                            <div class="event-post col-md-12">
                                <div class="post-img">
                                    <a href="{{ url('colloque/'.$colloque->id) }}">
                                        <img src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" alt=""/>
                                    </a>
                                    <span class="post-date"><span>{{ $colloque->start_at->format('d') }}</span> {{ $colloque->start_at->formatLocalized('%b') }}</span>
                                </div>
                                <div class="post-det">
                                    <h3><a href="{{ url('colloque/'.$colloque->id) }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                                    <span class="comments-num">{{ $colloque->soustitre }}</span>
                                    <p><i class="fa fa-calendar"></i>&nbsp; {{ $colloque->event_date }}</p>
                                    <p><strong>Lieu: </strong>
                                    {{ $colloque->location ? $colloque->location->name : '' }}, {!! $colloque->location ? strip_tags($colloque->location->adresse) : '' !!}</p>
                                    {!! $colloque->remarque !!}
                                    <p><a class="more-btn btn-sm" href="{{ url('colloque/'.$colloque->id) }}">Inscription</a></p>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        @endforeach
                    </section>
                @endforeach
            @endif

            <div class="b-post-bottom text-right">
                <a class="text-danger" href="{{ url('archives') }}"><i class="fa fa-calendar"></i> &nbsp;Archives</a>
            </div>

        </section>

        <section id="nouveautes" class="col-md-4 col-xs-12">

            <div class="heading-bar">
                <h2><i class="fa fa-heart"></i> &nbsp;Coups de coeur</h2>
                <span class="h-line"></span>
            </div>

            @if(!$nouveautes->isEmpty())
                <?php $chunks = $nouveautes->take(4); ?>
                @foreach($chunks as $product)
                        @include('frontend.pubdroit.partials.product', ['product' => $product, 'news' => false])
                @endforeach
            @endif

        </section>
    </section>

    <div class="heading-bar">
        <h2>Nouveautés</h2>
        <span class="h-line"></span>
    </div>

    @if(!$nouveautes->isEmpty())
    <?php $chunks = $nouveautes->chunk(3); ?>
        @foreach($chunks as $chunk)
            <section class="row">
                @foreach($chunk as $product)
                    <div class="col-md-4">
                       @include('frontend.pubdroit.partials.product', ['product' => $product, 'news' => true])
                    </div>
                @endforeach
            </section>
        @endforeach
    @endif

    <section class="row">
        <div class="heading-bar">
            <h2>Publications</h2>
            <span class="h-line"></span>
        </div>
        <!-- Start Main Content -->
        <section class="col-md-9 col-xs-12">
            <!-- Start Ad Slider Section -->
            <div class="blog-sec-slider">
                <div class="slider5">
                    <div class="slide"><a href="#"><img src="frontend/pubdroit/images/ad-book.jpg" alt=""/></a></div>
                </div>
            </div>
            <!-- End Ad Slider Section -->

            <!-- Start Grid View Section -->
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
                                <div class="readmore">{!! $product->description !!}</div>
                                <div class="cart-price">
                                    <form method="post" action="{{ url('cart/addProduct') }}" class="form-inline">{!! csrf_field() !!}
                                        <button type="submit" class="cart-btn2">Ajouter au panier</button>
                                        <span class="price">{{ $product->price_cents }} CHF</span>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    </form>
                                </div>
                            </div>
                        </article>

                    @endforeach
                @endif

            </section>
            <div class="blog-footer">
                <div class="pagination">
                    {!! $products->links() !!}
                </div>
            </div>
            <!-- End Grid View Section -->

        </section>
        <!-- End Main Content -->

        <!-- Start Main Side Bar -->
        <section class="col-md-3 col-xs-12">
            <div class="side-holder">
                <article class="banner-ad">
                    <img src="frontend/pubdroit/images/ad.jpg" alt="Helbing" />
                </article>
            </div>
        </section>
        <!-- End Main Side Bar -->
    </section>
    </section>
@stop
