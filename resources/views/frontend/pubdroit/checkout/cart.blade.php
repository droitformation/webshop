@extends('frontend.pubdroit.layouts.master')
@section('content')

<section class="row">
    <div class="col-md-12">

        <p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour au shop</a></p>

        <div class="heading-bar">
            <h2>1. Panier</h2>
            <span class="h-line"></span>
        </div>

        @include('frontend.pubdroit.partials.cart')
        @include('frontend.pubdroit.partials.cart-total')

    </div>
</section>

@stop