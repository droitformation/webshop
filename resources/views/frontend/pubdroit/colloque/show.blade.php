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
                    <div class="col-md-3 b-img-holder">
                        <span class="post-date"><span>{{ $colloque->start_at->format('d') }}</span> {{ $colloque->start_at->formatLocalized('%b') }}</span>
                        <span class='zoom' id='ex1'> <img src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" height="219" width="300" id='jack' alt=''/></span>
                    </div>
                    <!-- Strat Book Image Section -->

                    <!-- Strat Book Overview Section -->
                    <div class="col-md-9">
                        <strong class="title">{{ $colloque->titre }}</strong>
                        {!! !empty($colloque->soustitre) ? '<p class="text-muted">'.$colloque->soustitre.'</p>' : '' !!}
                        {!! $colloque->remarques !!}

                        <h4>{{ $colloque->event_date }}</h4>

                        <h5><strong><i class="fa fa-clock-o"></i> &nbsp;Délai d'inscription </strong> le {{ $colloque->registration_at->formatLocalized('%d %B %Y') }}</h5>

                        <h4>Prix</h4>

                        <?php $prices = $colloque->prices->whereLoose('type','public'); ?>
                        @if(!$prices->isEmpty())
                            <dl>
                                @foreach($prices as $price)
                                    <dt>{{ $price->description }}</dt>
                                    <dd>{{ $price->price_cents }} CHF</dd>
                                @endforeach
                            </dl>
                        @endif

                    </div>
                    <!-- End Book Overview Section -->
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
