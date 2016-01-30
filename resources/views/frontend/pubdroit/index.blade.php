@extends('layouts.pubdroit.master')

@section('content')

    <section class="row-fluid">
        <section class="span12 wellcome-msg m-bottom first">
            <h2>BIENVENUE sur publications-droit.ch.</h2>
            <p>Portail des divers publications de la Faculté de droit de l'Université de Neuchâtel</p>
        </section>
    </section>

    <section class="row-fluid">

        <section class="span8">

            <div class="heading-bar">
                <h2><i class="fa fa-calendar"></i> &nbsp;Prochains Evénements</h2>
                <span class="h-line"></span>
            </div>

            @if(!$colloques->isEmpty())
                <?php $chunks = $colloques->chunk(1); ?>
                @foreach($chunks as $chunk)
                    <section class="row-fluid">
                        @foreach($chunk as $colloque)

                            <div class="event-post span12">
                                <div class="post-img">
                                    <a href="{{ url('colloque/'.$colloque->id) }}">
                                        <img src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" alt=""/>
                                    </a>
                                    <span class="post-date"><span>{{ $colloque->start_at->format('d') }}</span> {{ $colloque->start_at->formatLocalized('%b') }}</span> </div>
                                <div class="post-det">
                                    <h3><a href="{{ url('colloque/'.$colloque->id) }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                                    <span class="comments-num">{{ $colloque->soustitre }}</span>
                                    <p><strong>Lieu: </strong>
                                    {{ $colloque->location ? $colloque->location->name : '' }}, {{ $colloque->location ? $colloque->location->adresse : '' }}</p>
                                    {!! $colloque->remarque !!}
                                    <p><a class="more-btn btn-sm" href="{{ url('colloque/'.$colloque->id) }}">Inscription</a></p>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        @endforeach
                    </section>
                @endforeach
            @endif

            <div class="b-post-bottom">
                <a class="more-btn" href="blog-detail.html">Archives</a>
            </div>

        </section>

        <section id="nouveautes" class="span4">

            <div class="heading-bar">
                <h2><i class="fa fa-heart"></i> &nbsp;Coups de coeur</h2>
                <span class="h-line"></span>
            </div>

            @if(!$nouveautes->isEmpty())
                <?php $chunks = $nouveautes->take(4); ?>
                @foreach($chunks as $product)
                    <figure class="s-product">
                        <div class="s-product-img">
                            <a href="{{ url('product/'.$product->id) }}"><img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/></a>
                        </div>
                        <article class="s-product-det">
                            <h3><a href="{{ asset('files/products/'.$product->image) }}">{{ $product->title }}</a></h3>
                            <p>{!! $product->teaser !!}</p>
                            <div class="cart-price">
                                <a href="cart.html" class="cart-btn2">Ajouter au panier</a>
                                <span class="price">{{ $product->price_cents }} CHF</span>
                            </div>
                        </article>
                        <div class="clearfix"></div>
                    </figure>
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
            <section class="row-fluid">
                @foreach($chunk as $product)
                <figure class="span4 s-product">
                    <div class="s-product-img">
                        <a href="{{ url('product/'.$product->id) }}">
                            <img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}"/>
                        </a>
                    </div>
                    <article class="s-product-det">
                        <h3><a href="{{ asset('files/products/'.$product->image) }}">{{ $product->title }}</a></h3>
                        <p>{!! $product->teaser !!}</p>
                        <div class="cart-price">
                            <a href="cart.html" class="cart-btn2">Ajouter au panier</a>
                            <span class="price">{{ $product->price_cents }} CHF</span>
                        </div>
                        <span class="sale-icon">Sale</span>
                    </article>
                </figure>
                @endforeach
            </section>
        @endforeach
    @endif

    <section class="row-fluid">
        <div class="heading-bar">
            <h2>Publications</h2>
            <span class="h-line"></span>
        </div>
        <!-- Start Main Content -->
        <section class="span9 first">
            <!-- Start Ad Slider Section -->
            <div class="blog-sec-slider">
                <div class="slider5">
                    <div class="slide"><a href="#"><img src="frontend/pubdroit/images/ad-book.jpg" alt=""/></a></div>
                </div>
            </div>
            <!-- End Ad Slider Section -->

            <!-- Start Grid View Section -->
            <div class="product_sort">
                <div class="row-1">
                    <div class="left">
                        <span class="s-title">Sort by</span>
                        <span class="list-nav">
                            <select name="">
                                <option>Position</option>
                                <option>Position 2</option>
                                <option>Position 3</option>
                                <option>Position 4</option>
                            </select>
                        </span>
                    </div>
                    <div class="right">
                        <span>Show</span>
                        <span>
                            <select name="">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </span>
                        <span>per page</span>
                    </div>
                </div>
                <div class="row-2">
                    <span class="left">Items 1 to 9 of 15 total</span>
                    <ul class="product_view">
                        <li>View as:</li>
                        <li><a class="grid-view" href="grid-view.html">Grid View</a></li>
                        <li><a class="list-view" href="list-view.html">List View</a></li>
                    </ul>
                </div>
            </div>
            <section class="list-holder">

                @if(!$products->isEmpty())
                    @foreach($products as $product)

                        <article class="item-holder">
                            <div class="span2">
                                <a href="{{ url('product/'.$product->id) }}"><img src="{{ asset('files/products/'.$product->image) }}" alt="{{ $product->title }}" /></a>
                            </div>
                            <div class="span10">
                                <div class="title-bar">
                                    <a href="{{ url('product/'.$product->id) }}">{{ $product->title }}</a>
                                    <span>{!! $product->teaser !!}</span>
                                </div>
                                <div class="readmore">{!! $product->description !!}</div>
                                <div class="cart-price">
                                    {!! Form::open(array('url' => 'cart/addProduct')) !!}
                                    {!! Form::hidden('_token', csrf_token()) !!}
                                    <button type="submit" class="cart-btn2"><i class="fa fa-shopping-cart"></i></button>
                                    <span class="price">{{ $product->price_cents }} CHF</span>
                                    {!! Form::hidden('product_id', $product->id) !!}
                                    {!! Form::close() !!}
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
        <section class="span3">
            <div class="side-holder">
                <article class="banner-ad">
                    <img src="frontend/pubdroit/images/ad.jpg" alt="Helbing" />
                </article>
            </div>

            <!-- Start Shop by Section -->
            <div class="side-holder">
                <article class="shop-by-list">
                    <h2>Trouver par</h2>
                    <div class="side-inner-holder">
                        @if(!$categories->isEmpty())
                            <strong class="title">Collections</strong>
                            <ul class="side-list">
                                @foreach($categories as $categorie_id => $categorie)
                                    <li><a href="{{ url('categorie/'.$categorie_id) }}">{{ $categorie }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!$domains->isEmpty())
                            <strong class="title">Thèmes</strong>
                            <ul class="side-list">
                                @foreach($domains as $domain_id => $domain)
                                    <li><a href="{{ url('domain/'.$domain_id) }}">{{ $domain }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!$authors->isEmpty())
                            <strong class="title">Auteur</strong>
                            <ul class="side-list">
                                @foreach($authors as $author_id => $author)
                                    <li><a href="{{ url('domain/'.$author_id) }}">{{ $author }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </article>
            </div>
            <!-- End Shop by Section -->

        </section>
        <!-- End Main Side Bar -->
    </section>
    </section>
@stop
