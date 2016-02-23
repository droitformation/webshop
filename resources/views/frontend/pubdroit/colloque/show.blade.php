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
                        <span class='zoom' id='ex1'> <img src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" height="219" width="300" id='jack' alt=''/></span>
                    </div>

                    <div class="col-md-9 colloque-inscription">

                        <h4><strong class="title">{{ $colloque->titre }}</strong></h4>
                        {!! !empty($colloque->soustitre) ? '<p class="text-muted">'.$colloque->soustitre.'</p>' : '' !!}
                        {!! $colloque->remarques !!}

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

                        <hr/>

                        <h4>Prix applicable</h4>

                        <div id="pricing">
                            <div class='wrapper'>

                                <?php $prices = $colloque->prices->whereLoose('type','public'); ?>
                                @if(!$prices->isEmpty())
                                    @foreach($prices as $price)
                                        <input type="radio" id="price_{{ $price->id }}" name="price_id" value="{{ $price->id }}">
                                        <label for="price_{{ $price->id }}">
                                            <div class='package'>
                                                <div class='name'>{{ $price->description }}</div>
                                                <div class='price_cents'>{{ $price->price_cents }} CHF</div>

                                                @if(!empty($price->remarque))
                                                    <hr/><p>{{ $price->remarque }}</p>
                                                @endif

                                            </div>
                                        </label>
                                    @endforeach
                                @endif

                            </div>
                            <div class="clearfix"></div>

                            <?php $types = $colloque->options->groupBy('type');
                    /*        echo '<pre>';
                            print_r($types);
                            echo '</pre>';*/
                            ?>
                            @if(!$types->isEmpty())
                                @foreach($types as $type => $options)

                                    <h4>Merci de préciser</h4>

                                    @if($type == 'checkbox')
                                        <?php
                                            $check =  'checkbox';
                                            $titre =  'Options';
                                        ?>

                                        <div class='wrapper'>
                                            @foreach($options as $option)
                                                <input type="{{ $check }}" id="option_{{ $option->id }}" name="option_id" value="{{ $option->id }}">
                                                <label for="option_{{ $option->id }}">
                                                    <div class='package'>
                                                        <div class='name'>{{ $option->title }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div class="clearfix"></div>

                                    @else
                                        <?php
                                            $check =  'radio';
                                            $titre =  'Options à choix';
                                        ?>
                                        @foreach($options as $option)
                                            <div class='wrapper'>
                                                <h4>{{ $option->title }}</h4>
                                                @foreach($option->groupe as $group)
                                                    <input type="{{ $check }}" id="group_{{ $group->id }}" name="option_id[{{ $option->id }}][]" value="{{ $group->id }}">
                                                    <label for="group_{{ $group->id }}">
                                                        <div class='package'>
                                                            <div class='name'>{{ $group->text }}</div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                                <div class="clearfix"></div>
                                            </div>
                                        @endforeach

                                    @endif




                                @endforeach
                            @endif
                        </div>

                        <div class="colloque-send">
                            <a href="#" class="more-btn">Envoyer</a>
                        </div>

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
