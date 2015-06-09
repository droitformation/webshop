@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour au shop</a>
        <h2>Resumé de la commande</h2>
    </div>
</div>

<!-- Cart  -->
@include('shop.partials.cart')

<div class="row bs-wizard" style="border-bottom:0;">

    <div class="col-xs-4 bs-wizard-step complete">
        <div class="text-center bs-wizard-stepnum">Login</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center"></div>
    </div>

    <div class="col-xs-4 bs-wizard-step active"><!-- complete -->
        <div class="text-center bs-wizard-stepnum">Adresse de livraison</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center"></div>
    </div>

    <div class="col-xs-4 bs-wizard-step disabled"><!-- complete -->
        <div class="text-center bs-wizard-stepnum">Confirmation</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center"></div>
    </div>

</div>

<div class="row" id="livraison">
    <div class="col-md-6">
        <h4>Adresse de livraison</h4>

        <address id="userAdresse">
            @include('shop.partials.user-livraison')
        </address>
        <p><a data-toggle="modal" data-target="#userFormModal" class="btn btn-default" href="#">Modifier votre adresse</a></p>

    </div>
</div>

<!-- User form for update  -->
@include('shop.partials.user-form')

<ul class="pager">
    <li class="next next-commander"><a href="{{ url('checkout/confirm') }}">Finaliser ma commande <span aria-hidden="true">&rarr;</span></a></li>
</ul>

@stop