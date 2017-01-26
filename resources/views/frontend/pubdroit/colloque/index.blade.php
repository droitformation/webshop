@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

            <div class="heading-bar">
                <h2>Colloques</h2>
                <a class="text-danger pull-right" href="{{ url('pubdroit/archives') }}"><i class="fa fa-calendar"></i> &nbsp;Archives</a>
                <span class="h-line"></span>
            </div>

            @if(!$colloques->isEmpty())
                <?php $chunks = $colloques->chunk(2); ?>
                @foreach($chunks as $chunk)
                    <section class="row">
                        @foreach($chunk as $colloque)

                            <div class="event-post col-md-6 col-xs-12">
                                <div class="post-img">
                                    <a href="{{ url('pubdroit/colloque/'.$colloque->id) }}">
                                        <img src="{{ asset($colloque->frontend_illustration) }}" height="219" width="300" alt='{{ $colloque->titre }}'/>
                                    </a>
                                    <span class="post-date"><span>{{ $colloque->start_at->format('d') }}</span> {{ $colloque->start_at->formatLocalized('%b') }}</span>
                                </div>
                                <div class="post-det">
                                    <h3><a href="{{ url('pubdroit/colloque/'.$colloque->id) }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                                    <span class="comments-num">{{ $colloque->soustitre }}</span>
                                    <p><strong>Lieu: </strong>
                                    {{ $colloque->location ? $colloque->location->name : '' }}, {{ $colloque->location ? strip_tags($colloque->location->adresse) : '' }}</p>
                                    {!! $colloque->remarque !!}
                                    <p><a class="more-btn btn-sm" href="{{ url('pubdroit/colloque/'.$colloque->id) }}">Inscription</a></p>
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
