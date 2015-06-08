@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Resumé de la commande</h2>
    </div>
</div>

<!-- Cart  -->
@include('partials.cart')

<div class="row" id="livraison">

    <div class="col-md-6">
        <h4>Adresse de livraison</h4>

        <?php $user->adresse_livraison->load(['pays','civilite']); ?>
        <address>
            <strong>{{ $user->adresse_livraison->civilite->title }} {{ $user->adresse_livraison->first_name }} {{ $user->adresse_livraison->last_name }}</strong><br>
            {!! !empty($user->adresse_livraison->company) ? $user->adresse_livraison->company.'<br>' : '' !!}
            {{ $user->adresse_livraison->adresse }}<br>
            {!! !empty($user->adresse_livraison->complement) ? $user->adresse_livraison->complement.'<br>' : '' !!}
            {!! !empty($user->adresse_livraison->cp) ? $user->adresse_livraison->cp.'<br>' : '' !!}
            {{ $user->adresse_livraison->npa }} {{ $user->adresse_livraison->ville }}<br>
            {{ $user->adresse_livraison->pays->title }}
        </address>

    </div>
</div>

<nav>
    <ul class="pager">
        <li class="previous"><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour au shop</a></li>
        <li class="next"><a href="{{ url('checkout/billing') }}">Finaliser ma commande <span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>

@stop