@if(isset($page))
    <section class="row">
        <div class="col-md-12">
            <div class="m-bottom wellcome-msg">
                <h2>{{ $page->title }}</h2>
                {!! $page->content !!}
            </div>
        </div>
    </section>
@endif

<section class="row">

    @if(!$colloques->isEmpty())
        <section class="col-md-8 col-xs-12">
            <div class="heading-bar">
                <h2><i class="fa fa-calendar"></i> &nbsp;Prochains événements</h2>
                <span class="h-line"></span>
            </div>

            <?php $chunks = $colloques->chunk(1); ?>
            @foreach($chunks as $chunk)
                <section class="row">
                    @foreach($chunk as $colloque)

                        <div class="event-post col-md-12">

                            <div class="post-img">
                                <a href="{{ url('colloque/'.$colloque->id) }}">
                                    <img src="{{ secure_asset($colloque->frontend_illustration) }}" height="219" width="300" alt='{{ $colloque->titre }}'/>
                                </a>
                                <span class="post-date"><span>{{ $colloque->start_at->format('d') }}</span> {{ $colloque->start_at->formatLocalized('%b') }}</span>
                            </div>
                            <div class="post-det">
                                <h3><a href="{{ url('pubdroit/colloque/'.$colloque->id) }}"><strong>{{ $colloque->titre }}</strong></a></h3>
                                <span class="comments-num">{{ $colloque->soustitre }}</span>
                                <p><i class="fa fa-calendar"></i>&nbsp; {{ $colloque->event_date }}</p>
                                <p><strong>Lieu: </strong>
                                    {{ $colloque->location ? $colloque->location->name : '' }}, {!! $colloque->location ? strip_tags($colloque->location->adresse) : '' !!}</p>
                                {!! $colloque->remarque !!}

                                @if($colloque->is_open)
                                    <p><a class="more-btn btn-sm" href="{{ url('pubdroit/colloque/'.$colloque->id) }}">Inscription</a></p>
                                @else
                                    <p class="text-danger text-right">COMPLET</p>
                                @endif

                            </div>
                            <div class="clearfix"></div>

                        </div>

                    @endforeach
                </section>
            @endforeach

        </section>
    @endif

    @if(!$nouveautes->isEmpty())
        <section id="nouveautes" class="col-md-4 col-xs-12">
            <div class="heading-bar">
                <h2><i class="fa fa-star"></i> &nbsp;Nouveautés</h2>
                <span class="h-line"></span>
            </div>

            <?php $chunks = $nouveautes->take(4); ?>
            @foreach($chunks as $product)
                @include('frontend.pubdroit.partials.product', ['product' => $product, 'news' => false])
            @endforeach
        </section>
    @endif

</section>

@if(!$abos->isEmpty())
    <section id="abos">
        <div class="row">
            <div class="col-md-12">
                <div class="heading-bar">
                    <h2><i class="fa fa-bookmark"></i> &nbsp;Abonnements</h2>
                    <span class="h-line"></span>
                </div>
            </div>
        </div>

        <?php $chunks = $abos->chunk(3); ?>
        @foreach($chunks as $chunk)
            <div class="row">
                @foreach($chunk as $product)
                    <div class="col-md-4">
                        @include('frontend.pubdroit.partials.abo', ['product' => $product])
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>
@endif
