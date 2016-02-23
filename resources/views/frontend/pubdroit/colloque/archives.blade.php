@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

            <div class="heading-bar">
                <h2>Archives colloques</h2>
                <span class="h-line"></span>
            </div>

            @if(!$colloques->isEmpty())
                <?php $chunks = $colloques->chunk(2); ?>
                @foreach($chunks as $chunk)
                    <section class="row">
                        @foreach($chunk as $colloque)

                            <div class="event-post col-md-6">
                                <div class="post-img">
                                    <a href="{{ url('colloque/'.$colloque->id) }}">
                                        <?php $illustraton = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                                        <img src="{{ asset('files/colloques/illustration/'.$illustraton) }}" alt=""/>
                                    </a>
                                </div>
                                <div class="post-det">
                                    <h3><a href="{{ url('colloque/'.$colloque->id) }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                                    <span class="comments-num">{{ $colloque->soustitre }}</span>
                                    <p><i class="fa fa-calendar"></i> &nbsp;{{ $colloque->event_date }}</p>
                                    <p><strong>Lieu: </strong>
                                        {{ $colloque->location ? $colloque->location->name : '' }}, {{ $colloque->location ? $colloque->location->adresse : '' }}</p>
                                    {!! $colloque->remarque !!}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        @endforeach
                    </section>
                @endforeach
            @endif

        </div>
    </section>

@stop
