@extends('frontend.pubdroit.layouts.master')
@section('content')

        <!-- Start Main Content -->
<section class="row">
    <div class="col-md-12">

        <p><a href="{{ url('shop') }}"><span aria-hidden="true">&larr;</span> Retour au shop</a></p>

        <div class="heading-bar">
            <h2>Résumé de votre commande</h2>
            <span class="h-line"></span>
        </div>

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

        <!-- Start Accordian Section -->
        @include('frontend.pubdroit.partials.cart')

    </div>
</section>

@stop