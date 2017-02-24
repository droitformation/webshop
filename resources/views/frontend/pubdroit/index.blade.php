@extends('frontend.pubdroit.layouts.master')
@section('content')

    @if($products->onFirstPage())
        @include('frontend.pubdroit.partials.home-header')
    @else
        <p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>
    @endif

    <section class="row" id="product_list">
        <!-- Start Main Content -->
        <section class="col-md-9 col-xs-12">

            @if(!$products->isEmpty())

                <div class="heading-bar">
                    <h2><i class="fa fa-book"></i> &nbsp;Publications</h2>
                    <span class="h-line"></span>
                </div>

                <!-- Start Grid View Section -->
                <section class="list-holder">

                    @foreach($products as $product)

                        <article class="item-holder">
                            <div class="col-md-2">
                                <a href="{{ url('pubdroit/product/'.$product->id) }}">
                                    <img src="{{ secure_asset('files/products/'.$product->image) }}" alt="{{ $product->title }}" />
                                </a>
                            </div>
                            <div class="col-md-10">
                                <div class="title-bar">
                                    <a href="{{ url('pubdroit/product/'.$product->id) }}">{{ $product->title }}</a>
                                    <span>{!! $product->teaser !!}</span>
                                </div>
                                <div class="readmore product-description">{!! $product->description !!}</div>
                                <!-- Product put in the basket button -->
                                @include('frontend.pubdroit.partials.basket')
                                <!-- END Product put in the basket button -->
                            </div>
                        </article>

                    @endforeach

                    <div class="blog-footer">
                        <div class="pagination">
                            {!! $products->links() !!}
                        </div>
                    </div>
                    <!-- End Main Content -->

                </section>
            @endif
        </section>

        <!-- Start Main Side Bar -->
        <section class="col-md-3 col-xs-12">
            <div class="side-holder">
                <article class="banner-ad">
                    <!-- Bloc contenus -->
                    @if(isset($page) && !$page->blocs->isEmpty())
                        @foreach($page->blocs as $bloc)
                            <div class="sidebar-content-bloc">
                                @include('frontend.partials.bloc', ['bloc' => $bloc])
                            </div>
                        @endforeach
                    @endif
                </article>
            </div>
        </section>
        <!-- End Main Side Bar -->
    </section>
    </div>
@stop
