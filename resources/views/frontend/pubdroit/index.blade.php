@extends('layouts.pubdroit.master')

@section('content')

    <section class="row-fluid">
        <section class="span12 wellcome-msg m-bottom first">
            <h2>BIENVENUE sur publications-droit.ch.</h2>
            <p>Offering a wide selection of books on thousands of topics at low prices to satisfy any book-lover!</p>
        </section>
    </section>
    <section class="row-fluid ">
        <figure class="span4 s-product">
            <div class="s-product-img"><a href="book-detail.html"><img src="frontend/pubdroit/images/image02.jpg" alt="Image02"/></a></div>
            <article class="s-product-det">
                <h3><a href="book-detail.html">A Walk Across The Sun</a></h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod dolore magna aliqua.</p>
                <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span>
                <div class="cart-price"> <a href="cart.html" class="cart-btn2">Add to Cart</a> <span class="price">$129.90</span> </div>
                <span class="sale-icon">Sale</span> </article>
        </figure>
        <figure class="span4 s-product">
            <div class="s-product-img"><a href="book-detail.html"><img src="frontend/pubdroit/images/image03.jpg" alt="Image02"/></a></div>
            <article class="s-product-det">
                <h3><a href="book-detail.html">Harry Potter</a></h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod dolore magna aliqua.</p>
                <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span>
                <div class="cart-price"> <a href="cart.html" class="cart-btn2">Add to Cart</a> <span class="price">$44.95</span> </div>
                <span class="sale-icon">Sale</span> </article>
        </figure>
        <figure class="span4 s-product">
            <div class="s-product-img"><a href="book-detail.html"><img src="frontend/pubdroit/images/image04.jpg" alt="Image02"/></a></div>
            <article class="s-product-det">
                <h3><a href="book-detail.html">Sleeping Beauty</a></h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod dolore magna aliqua.</p>
                <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span>
                <div class="cart-price"> <a href="cart.html" class="cart-btn2">Add to Cart</a> <span class="price">$223.00</span> </div>
                <span class="sale-icon">Sale</span> </article>
        </figure>
    </section>
    <!-- Start BX Slider holder -->
    <section class="row-fluid features-books">
        <section class="span12 m-bottom">
            <div class="heading-bar">
                <h2>Featured Books</h2>
                <span class="h-line"></span> </div>
            <div class="slider1">

                @if(!$products->isEmpty())
                    @foreach($products as $product)
                        <div class="slide">
                            <a href="{{ url('product/'.$product->id) }}"><img src="{{ asset('files/products/'.$product->image) }}" alt=""></a>
                            <span class="title">
                                <a href="{{ url('product/'.$product->id) }}">{{ $product->title }}</a>
                            </span>
                            <span class="rating-bar">
                                <img style="max-width: 140px;" src="{{ asset('frontend/pubdroit/images/rating-star.png') }}" alt="Rating Star"/>
                            </span>
                            <div class="cart-price">
                                {!! Form::open(array('url' => 'cart/addProduct')) !!}
                                    {!! Form::hidden('_token', csrf_token()) !!}
                                    <button type="submit" class="cart-btn2">Ajouter au panier</button>
                                    {!! Form::hidden('product_id', $product->id) !!}
                                {!! Form::close() !!}
                                <span class="price">{{ $product->price_cents }} CHF</span>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </section>
    </section>
    <!-- End BX Slider holder -->
    <!-- Start Featured Author -->
    <section class="row-fluid m-bottom">
        <section class="span9">
            <div class="featured-author">
                <div class="left"> <span class="author-img-holder"><a href="about-us.html"><img src="frontend/pubdroit/images/image16.jpg" alt=""/></a></span>
                    <div class="author-det-box">
                        <div class="ico-holder">
                            <div id="socialicons" class="hidden-phone"> <a id="social_linkedin" class="social_active" href="#" title="Visit Google Plus page"><span></span></a> <a id="social_facebook" class="social_active" href="#" title="Visit Facebook page"><span></span></a> <a id="social_twitter" class="social_active" href="#" title="Visit Twitter page"><span></span></a> </div>
                        </div>
                        <div class="author-det"> <span class="title">Featured Author</span> <span class="title2">Mr. Khalid Hosseini</span>
                            <ul class="books-list">
                                <li><a href="book-detail.html"><img src="frontend/pubdroit/images/image11.jpg" alt=""/></a></li>
                                <li><a href="book-detail.html"><img src="frontend/pubdroit/images/image12.jpg" alt=""/></a></li>
                                <li><a href="book-detail.html"><img src="frontend/pubdroit/images/image13.jpg" alt=""/></a></li>
                                <li><a href="book-detail.html"><img src="frontend/pubdroit/images/image14.jpg" alt=""/></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="current-book"> <strong class="title"><a href="book-detail.html">The Kite Runner</a></strong>
                        <p>Lorem ipsum dolor slo nsec  tueraliquet rbi nec In nisl lorem in ...</p>
                        <div class="cart-price"> <a href="cart.html" class="cart-btn2">Add to Cart</a> <span class="price">$129.90</span> </div>
                    </div>
                    <div class="c-b-img"> <a href="book-detail.html"><img src="frontend/pubdroit/images/image17.jpg" alt="" /></a> </div>
                </div>
            </div>
        </section>
        <section class="span3 best-sellers">
            <div class="heading-bar">
                <h2>Best Sellers</h2>
                <span class="h-line"></span> </div>
            <div class="slider2">
                <div class="slide"><a href="book-detail.html"><img src="frontend/pubdroit/images/image15.jpg" alt=""/></a>
                    <div class="slide2-caption">
                        <div class="left"> <span class="title"><a href="book-detail.html">Thousand Splendid Miles</a></span> <span class="author-name">by Khalid Housseini</span> </div>
                        <div class="right"> <span class="price">$139.50</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
                    </div>
                </div>
                <div class="slide"><a href="book-detail.html"><img src="frontend/pubdroit/images/image15.jpg" alt=""/></a>
                    <div class="slide2-caption">
                        <div class="left"> <span class="title"><a href="book-detail.html">Thousand Splendid Miles</a></span> <span class="author-name">by Khalid Housseini</span> </div>
                        <div class="right"> <span class="price">$139.50</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
                    </div>
                </div>
                <div class="slide"><a href="book-detail.html"><img src="frontend/pubdroit/images/image15.jpg" alt=""/></a>
                    <div class="slide2-caption">
                        <div class="left"> <span class="title"><a href="book-detail.html">Thousand Splendid Miles</a></span> <span class="author-name">by Mr. Khalid </span> </div>
                        <div class="right"> <span class="price">$139.50</span> <span class="rating-bar"><img src="frontend/pubdroit/images/rating-star.png" alt="Rating Star"/></span> </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- End Featured Author -->
    <section class="row-fluid m-bottom">
        <!-- Start Blog Section -->
        <section class="span9 blog-section">
            <div class="heading-bar">
                <h2>Latest from the Blog</h2>
                <span class="h-line"></span> </div>
            <div class="slider3">
                <div class="slide">
                    <div class="post-img"><a href="blog-detail.html"><img src="frontend/pubdroit/images/image18.jpg" alt=""/></a> <span class="post-date"><span>02</span> May</span> </div>
                    <div class="post-det">
                        <h3><a href="blog-detail.html">Our latest arrival is the Spring Summer 2013 Book Fair</a></h3>
                        <span class="comments-num">6 comments</span>
                        <p>Gluten-free quinoa selfies carles, kogi gentrify retro marfa viral. Aesthetic before they sold out put a bird on it sriracha typewriter. Skateboard viral irony tonx ... </p>
                    </div>
                </div>
                <div class="slide">
                    <div class="post-img"><a href="blog-detail.html"><img src="frontend/pubdroit/images/image29.jpg" alt=""/></a> <span class="post-date"><span>24</span> Oct</span> </div>
                    <div class="post-det">
                        <h3><a href="blog-detail.html">Our latest arrival is the Spring Summer 2012 Book Fair</a></h3>
                        <span class="comments-num">48 comments</span>
                        <p>Gluten-free quinoa selfies carles, kogi gentrify retro marfa viral. Aesthetic before they sold out put a bird on it sriracha typewriter. Skateboard viral irony tonx ... </p>
                    </div>
                </div>
                <div class="slide">
                    <div class="post-img"><a href="blog-detail.html"><img src="frontend/pubdroit/images/image30.jpg" alt=""/></a> <span class="post-date"><span>10</span> Aug</span> </div>
                    <div class="post-det">
                        <h3><a href="blog-detail.html">Our latest arrival is the Spring Summer 2011 Book Fair</a></h3>
                        <span class="comments-num">24 comments</span>
                        <p>Gluten-free quinoa selfies carles, kogi gentrify retro marfa viral. Aesthetic before they sold out put a bird on it sriracha typewriter. Skateboard viral irony tonx ... </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Blog Section -->
        <!-- Start Testimonials Section -->
        <section class="span3 testimonials">
            <div class="heading-bar">
                <h2>Testimonials</h2>
                <span class="h-line"></span> </div>
            <div class="slider4">
                <div class="slide">
                    <div class="author-name-holder"> <img src="frontend/pubdroit/images/image19.png" /> </div>
                    <strong class="title">Robert Smith <span>Manager</span></strong>
                    <div class="slide">
                        <p>Lorem ipsum dolor slo onsec nelioro tueraliquet Morbi nec In Curabitur lorem in design Morbi nec In Curabituritus gojus, </p>
                    </div>
                </div>
                <div class="slide">
                    <div class="author-name-holder"> <img src="frontend/pubdroit/images/image19.png" /> </div>
                    <strong class="title">Mr. Khalid Hosseini <span>Manager</span></strong>
                    <div class="slide">
                        <p>Gluten-free quinoa selfies carles, kogi gentrify retro marfa viral. Aesthetic before they sold out put a bird on it sriracha typewriter. Skateboard viral irony tonx ... </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonials Section -->
    </section>

@stop
