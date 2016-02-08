@extends('frontend.pubdroit.layouts.master')
@section('content')

    <!-- Start Main Content -->
    <section class="row" id="livraison">
        <div class="col-md-12">
            <h4>Adresse de livraison</h4>

            <address id="userAdresse">
                @include('frontend.pubdroit.partials.user-livraison')
            </address>
            <p><a data-toggle="modal" data-target="#userFormModal" class="btn btn-default" href="#">Modifier votre adresse</a></p>
        </div>
    </section>
    <!-- End Main Content -->
    <div class="row">
        <section class="col-md-12">
            <!-- Start Accordian Section -->
            @include('frontend.pubdroit.partials.cart')
        </section>
    </div>
    <!-- End Main Content -->

    <!-- User form for update  -->
    @include('frontend.pubdroit.partials.user-form')

@stop