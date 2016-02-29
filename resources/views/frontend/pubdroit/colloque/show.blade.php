@extends('frontend.pubdroit.layouts.master')
@section('content')

    <section class="row">
        <div class="col-md-12">

            <p><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour à l'accueil</a></p>

            <div class="heading-bar">
                <h2>Colloque</h2>
                <span class="h-line"></span>
            </div>

            <!-- Strat Book Detail Section -->
            <section class="b-detail-holder">
                <div class="book-i-caption">
                    <!-- Strat Book Image Section -->
                    <div class="col-md-3">
                        <div class="b-img-holder">
                            <span class='zoom' id='ex1'>
                                <img src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" height="219" width="300" id='jack' alt=''/>
                            </span>
                        </div>

                        @if(!$colloque->documents->isEmpty())
                            @foreach($colloque->documents as $document)
                                <?php $file = 'files/colloques/'.$document->type.'/'.$document->path; ?>
                                @if (File::exists($file) && ($document->type == 'programme' || $document->type == 'document'))
                                    <p><a class="btn btn-inverse btn-sm btn-block" target="_blank" href="{{ asset($file) }}">{{ !empty($document->titre) ? $document->titre : 'Programme' }}</a></p>
                                @endif
                            @endforeach
                        @endif

                        @if($colloque->location && $colloque->location->location_map)
                            <p><a target="_blank" class="btn btn-default btn-sm btn-block" href="{{ asset($colloque->location->location_map) }}">Plan d'accès</a></p>
                        @endif

                    </div>
                    <div class="col-md-9 colloque-inscription">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="calltoaction">
                                    <p><i class="fa fa-calendar"></i>&nbsp; {{ $colloque->event_date }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="calltoaction calltored">
                                    <p><strong><i class="fa fa-clock-o"></i></strong>  &nbsp;Délai d'inscription: {{ $colloque->registration_at->formatLocalized('%d %B %Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <h4><strong class="title">{{ $colloque->titre }}</strong></h4>
                        {!! !empty($colloque->soustitre) ? '<p class="text-muted">'.$colloque->soustitre.'</p>' : '' !!}
                        {!! $colloque->remarques !!}

                        <hr/>

                        @if(Auth::check())
                            @include('frontend.pubdroit.colloque.partials.register')
                        @else
                            <div class="login-form">
                                <div class="panel panel-inverse">
                                    @include('auth.partials.login-form', ['returnPath' => Request::url()])
                                </div>

                                <p class="line-delimiter">Ou</p>

                                <p><a href="{{ url('auth/register') }}" class="btn btn-block btn-primary">Je n'ai pas encore de compte</a></p>
                            </div>
                         @endif
                    </div>
                </div>

                <hr/>

                <div class="col-md-12 colloque-centers">
                    @if(isset($colloque->centres))
                        @foreach($colloque->centres as $center)
                            <a href="{{ $center->url }}"><img style="max-width: 45%; max-height: 55px;" src="{{ asset('files/logos/'.$center->logo) }}"></a>
                        @endforeach
                    @endif
                </div>

            </section>
            <!-- Strat Book Detail Section -->

        </div>
    </section>

@stop
