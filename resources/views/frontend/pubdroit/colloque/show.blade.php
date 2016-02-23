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
                        <form role="form" id="colloque-inscription" method="POST" action="{{ url('registration') }}">
                            {!! csrf_field() !!}

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

                            <h4>Prix applicable</h4>

                            <div id="colloque-dependence">

                                <div class='wrapper'>

                                    <?php $prices = $colloque->prices->whereLoose('type','public'); ?>
                                    @if(!$prices->isEmpty())
                                        @foreach($prices as $price)
                                            <input class="prices" required type="radio" id="price_{{ $price->id }}" name="price_id" value="{{ $price->id }}">
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
                                    <div class="clearfix"></div>
                                </div>

                                <hr/>

                                <?php $types = $colloque->options->groupBy('type'); ?>

                                @if(!$types->isEmpty())
                                    @foreach($types as $type => $options)

                                        @if($type == 'checkbox')
                                            <br/><h4>Merci de préciser &nbsp;<small class="text-muted">(facultatif)</small></h4>
                                            <?php $check = 'checkbox'; ?>
                                            <div class='wrapper'>
                                                @foreach($options as $option)
                                                    <input class="options" type="{{ $check }}" id="option_{{ $option->id }}" name="option_id" value="{{ $option->id }}">
                                                    <label for="option_{{ $option->id }}">
                                                        <div class='package'>
                                                            <div class='name option_name'>{{ $option->title }}</div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                                <div class="clearfix"></div>
                                            </div>

                                        @else
                                            <br/><h4>Merci de préciser</h4>
                                            <?php $check = 'radio'; $titre =  'Options à choix'; ?>
                                            @foreach($options as $option)
                                                <div class='wrapper'>
                                                    <p>{{ $option->title }} &nbsp;<div class="errorTxt"></div></p>

                                                    @foreach($option->groupe as $group)
                                                        <input class="options" type="{{ $check }}" required id="group_{{ $group->id }}" name="option_id[{{ $option->id }}][]" value="{{ $group->id }}">
                                                        <label for="group_{{ $group->id }}">
                                                            <div class='package'>
                                                                <div class='name option_name'>{{ $group->text }}</div>
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

                            <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                            <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                            <div class="colloque-send"><button type="submit" href="#" class="more-btn">Envoyer</button></div>

                        </form>

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
