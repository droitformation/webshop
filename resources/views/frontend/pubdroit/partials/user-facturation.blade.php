<?php
    // reload adresse to reflect eventual changes
    $user->load('adresses');
    $user->adresse_facturation->load(['pays','civilite']);
?>
{!! !empty($user->adresse_facturation->civilite_title) ? $user->adresse_facturation->civilite_title.'<br>' : '' !!}
{{ $user->adresse_facturation->first_name }} {{ $user->adresse_facturation->last_name }}<br>
{!! !empty($user->adresse_facturation->company) ? $user->adresse_facturation->company.'<br>' : '' !!}
{{ $user->adresse_facturation->adresse }}<br>
{!! !empty($user->adresse_facturation->complement) ? $user->adresse_facturation->complement.'<br>' : '' !!}
{!! !empty($user->adresse_facturation->cp) ? $user->adresse_facturation->cp.'<br>' : '' !!}
{{ $user->adresse_facturation->npa }} {{ $user->adresse_facturation->ville }}<br>
{{ $user->adresse_facturation->pays->title }}