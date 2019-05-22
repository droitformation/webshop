@extends('frontend.pubdroit.layouts.master2019')
@section('content')

    <section class="list-cards colloque-cards">

    </section>

    <section class="list-cards">
        @if(!$colloques->isEmpty())
            @foreach($colloques as $colloque)
                <article class="colloque-card">
                    <a class="colloque-card-thumb" href="{{ $colloque->register_url }}">
                        <img src="{{ secure_asset($colloque->frontend_illustration) }}" alt='{{ $colloque->titre }}'/>
                    </a>
                    <div class="colloque-card-infos">
                        <h3><a href="{{ $colloque->register_url }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                        <span class="comments-num">{{ $colloque->soustitre }}</span>
                        <p><i class="fa fa-calendar"></i>&nbsp; {{ $colloque->event_date }}</p>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <h3><a href="{{ $colloque->register_url }}"><strong>{{ $colloque->titre }}</strong></a></h3>
    <span class="comments-num">{{ $colloque->soustitre }}</span>
    <p><i class="fa fa-calendar"></i>&nbsp; {{ $colloque->event_date }}</p>
    <p><strong>Lieu: </strong>
        {{ $colloque->location ? $colloque->location->name : '' }}, {!! $colloque->location ? strip_tags($colloque->location->adresse) : '' !!}</p>
    {!! $colloque->remarque !!}

    <div class="btn-group pull-right">
        @if($colloque->is_open)
            <a class="more-btn btn-sm" href="{{ $colloque->register_url }}">Inscription</a>
        @else
            <p class="text-danger">COMPLET</p>
        @endif
    </div>

@stop
