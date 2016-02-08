@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <section class="col-md-12 cart-holder">

            <div class="heading-bar">
                <h2>PANIER</h2>
                <span class="h-line"></span>
            </div>

            <!-- Cart  -->
            @include('frontend.pubdroit.partials.cart')
            @include('frontend.pubdroit.partials.cart-total')

        </section>
    </section>

@stop