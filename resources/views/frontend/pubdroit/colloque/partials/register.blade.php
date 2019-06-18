
<div id="colloque-dependence">

    <form id="appVue">
        <form id="inscriptionForm" method="POST" action="{{ url('pubdroit/registration') }}">{!! csrf_field() !!}
            <form-wizard @on-complete="onComplete">
                <tab-content title="Prix" :before-change="beforeTabSwitch">
                    @include('frontend.pubdroit.colloque.wizard.price')
                </tab-content>
                <tab-content title="Options" :before-change="beforeTabSwitch">
                    @include('frontend.pubdroit.colloque.wizard.option')
                </tab-content>
                @if(!$colloque->occurrences->isEmpty())
                    <tab-content title="Ateliers" :before-change="beforeTabSwitch">
                        @include('frontend.pubdroit.colloque.wizard.occurrence')
                    </tab-content>
                @endif
                <tab-content title="Adresse">
                    <h4>Vérifier l'adresse</h4>

                    <?php $adresse_livraison   = $user->adresse_livraison ? $user->adresse_livraison : null; ?>
                    <?php $adresse_facturation = $user->adresse_facturation ? $user->adresse_facturation : null; ?>

                    <facturation-adresse
                            :main="{{ $adresse_livraison->id }}"
                            :livraison="{{ json_encode($adresse_livraison) }}"
                            :facturation="{{ json_encode($adresse_facturation) }}">
                    </facturation-adresse>
                </tab-content>
                <tab-content title="Confirmer">

                    <h4>Fin</h4>
                    <p>You have successfully completed all steps.</p>

                    <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                    <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                </tab-content>
            </form-wizard>
        </form>
    </div>

    <form id="wizard" method="POST" action="{{ url('pubdroit/registration') }}">{!! csrf_field() !!}

        <h1>Prix <span class="round-tab"><i class="fa fa-money"></i></span></h1>
        <fieldset>
            @if(!$colloque->prices_active->isEmpty())
                <h4>Prix applicable</h4>
                <div class='wrapper'>
                    @foreach($colloque->prices_active as $price)
                        <div class="item_wrapper">
                            <input class="prices" required type="radio" id="price_{{ $price->id }}" name="price_id" value="{{ $price->id }}">
                            <label id="label_price_{{ $price->id }}" for="price_{{ $price->id }}">
                                <div class='package'>
                                    <div class='name'>{{ $price->description }}</div>
                                    <div class='price_cents'>{{ $price->price_cents > 0 ? $price->price_cents.' CHF' : 'Gratuit' }}</div>
                                    {!! !empty($price->remarque) ? '<hr/><p>'.$price->remarque.'</p>' : '' !!}
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <hr/>
            @endif
        </fieldset>

        <h1>Options <span class="round-tab"><i class="fa fa-tags"></i></span></h1>
        <fieldset>
            <div class="options-list">

                <?php $types = $colloque->options->groupBy('type'); ?>

                @if(!$types->isEmpty())
                    @foreach($types as $type => $options)

                        @if($type == 'checkbox')
                            <h5>Merci de préciser &nbsp;<small class="text-muted">(facultatif)</small></h5>
                            <div class='wrapper'>
                                @foreach($options as $option)
                                    <div class="item_wrapper">
                                        <input class="options" type="checkbox" id="option_{{ $option->id }}" name="options[]" value="{{ $option->id }}">
                                        <label for="option_{{ $option->id }}">
                                            <div class='package'><div class='name option_name'>{{ $option->title }}</div></div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($type == 'text')
                            <h4>Merci de préciser</h4>
                            <div class='wrapper'>
                                @foreach($options as $option)
                                    <div class="item_wrapper">
                                        <label for="option_{{ $option->id }}">
                                            <div class='package'><div class='name option_name'>{{ $option->title }}</div></div>
                                        </label>
                                        <textarea class="form-control" id="option_{{ $option->id }}" name="options[][{{ $option->id }}]"></textarea>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($type == 'choix')
                            <h4>Merci de préciser</h4>
                            @foreach($options as $option)
                                <p class="description_option">{{ strip_tags($option->title) }} &nbsp;<div class="errorTxt"></div></p>
                                <div class='wrapper'>
                                    @foreach($option->groupe as $group)
                                        <div class="item_wrapper">
                                            <input class="options" type="radio" required id="group_{{ $group->id }}" name="groupes[{{ $option->id }}]" value="{{ $group->id }}">
                                            <label for="group_{{ $group->id }}">
                                                <div class='package'><div class='name option_name'>{{ $group->text }}</div></div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                    @endforeach
                @endif
            </div>
        </fieldset>

        @if(!$colloque->occurrences->isEmpty())
            <h1>Ateliers <span class="round-tab"><i class="fa fa-map-marker"></i></span></h1>
            <fieldset>
                <h5>Merci de préciser</h5>

                <?php $dates = $colloque->occurrences->pluck('starting_at')->unique(); ?>

                @foreach($colloque->occurrences as $occurrence)
                    <input class="options occurrences" {{ $occurrence->is_active && $occurrence->is_open ? 'required' : 'disabled' }} type="{{ $dates->count() > 1 ? 'checkbox' : 'radio' }}" id="occurrence_{{ $occurrence->id }}" name="occurrences[]" required value="{{ $occurrence->id }}">
                    <label for="occurrence_{{ $occurrence->id }}">
                        <div class='package'>
                            <div class='name'>{{ $occurrence->title }} {{ $occurrence->full || !$occurrence->is_open ? 'COMPLET' : '' }}</div>
                            <div class='occurrence_date'>Date: {{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}</div>
                            <div class='occurrence_date occurrence_location'>{{ $occurrence->location->name }}</div>
                        </div>
                    </label>
                @endforeach
            </fieldset>
        @endif

        <h1>Adresse <span class="round-tab"><i class="fa fa-home"></i></span></h1>
        <fieldset>

        </fieldset>

        <h1>Confirmer <span class="round-tab"><i class="fa fa-check"></i></span></h1>
        <fieldset>
            <h4>Fin</h4>

            <p>You have successfully completed all steps.</p>

            <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
            <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

        </fieldset>
    </form>
</div>


{{--
<form role="form" id="colloque-inscription" method="POST" action="{{ url('pubdroit/registration') }}">{!! csrf_field() !!}
    <h3>Inscription</h3>
    @include('frontend.pubdroit.colloque.partials.wizard')
</form>
--}}
