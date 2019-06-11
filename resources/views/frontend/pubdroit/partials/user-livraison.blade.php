<?php
    // reload adresse to reflect eventual changes
    $user->load('adresses');
    $user->adresse_livraison->load(['pays','civilite']);
?>
<strong>{{ $user->adresse_livraison->civilite_title }} {{ $user->adresse_livraison->first_name }} {{ $user->adresse_livraison->last_name }}</strong><br>
{!! !empty($user->adresse_livraison->company) ? $user->adresse_livraison->company.'<br>' : '' !!}
{{ $user->adresse_livraison->adresse }}<br>
{!! !empty($user->adresse_livraison->complement) ? $user->adresse_livraison->complement.'<br>' : '' !!}
{!! !empty($user->adresse_livraison->cp) ? $user->adresse_livraison->cp.'<br>' : '' !!}
{{ $user->adresse_livraison->npa }} {{ $user->adresse_livraison->ville }}<br>
{{ $user->adresse_livraison->pays->title }}