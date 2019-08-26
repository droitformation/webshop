<div class="wizard">
    <div class="wizard-inner">
        <ul class="nav nav-tabs" role="tablist">

            <li role="presentation" class="active">
                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Prix"><span class="round-tab"><i class="fa fa-money"></i></span></a>
            </li>

            <li role="presentation" class="disabled">
                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Options"><span class="round-tab"><i class="fa fa-tags"></i></span></a>
            </li>

            @if(!$colloque->occurrences->isEmpty())
                <li role="presentation" class="disabled">
                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Ateliers"><span class="round-tab"><i class="fa fa-map-marker"></i></span></a>
                </li>
            @endif

            <li role="presentation" class="disabled">
                <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Adresse"><span class="round-tab"><i class="fa fa-home"></i></span></a>
            </li>

            <li role="presentation" class="disabled">
                <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Terminé"><span class="round-tab"><i class="fa fa-check"></i></span></a>
            </li>
        </ul>
    </div>


    <div id="colloque-dependence">
        <div class="tab-content">
            <div class="tab-pane active" role="tabpanel" id="step1">
                @if(!$colloque->prices_active->isEmpty())
                    <div class='wrapper'>
                        <h4>Prix applicable</h4>
                        @foreach($colloque->prices_active as $price)
                            <input class="prices" required type="radio" id="price_{{ $price->id }}" name="price_id" value="{{ $price->id }}">
                            <label id="label_price_{{ $price->id }}" for="price_{{ $price->id }}">
                                <div class='package'>
                                    <div class='name'>{{ $price->description }}</div>
                                    <div class='price_cents'>{{ $price->price_cents > 0 ? $price->price_cents.' CHF' : 'Gratuit' }}</div>
                                    {!! !empty($price->remarque) ? '<hr/><p>'.$price->remarque.'</p>' : '' !!}
                                </div>
                            </label>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                    <hr/>
                @endif
                <ul class="list-inline">
                    <li></li>
                    <li class="text-right"><button type="button" class="btn btn-primary btn-lg next-step">Sauver et continuer &nbsp;<i class="fa fa-arrow-right"></i></button></li>
                </ul>
            </div>

            <div class="tab-pane" role="tabpanel" id="step2">
                <!-- Options -->
                <h4>Options</h4>
                <div class="options-list">

                    <?php $types = $colloque->options->groupBy('type'); ?>
                    @if(!$types->isEmpty())
                        @foreach($types as $type => $options)

                            @if($type == 'checkbox')
                                <h5>Merci de préciser &nbsp;<small class="text-muted">(facultatif)</small></h5>
                                <?php $check = 'checkbox'; ?>
                                <div class='wrapper'>
                                    @foreach($options as $option)
                                        <input class="options" type="{{ $check }}" id="option_{{ $option->id }}" name="options[]" value="{{ $option->id }}">
                                        <label for="option_{{ $option->id }}">
                                            <div class='package'>
                                                <div class='name option_name'>{{ $option->title }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                    <div class="clearfix"></div>
                                </div>
                            @endif
                            @if($type == 'text')
                                <h4>Merci de préciser</h4>
                                <div class='wrapper'>
                                    @foreach($options as $option)
                                        <label for="option_{{ $option->id }}">
                                            <div class='package'>
                                                <div class='name option_name'>{{ $option->title }}</div>
                                            </div>
                                        </label>
                                        <textarea class="form-control" id="option_{{ $option->id }}" name="options[][{{ $option->id }}]"></textarea>
                                    @endforeach
                                    <div class="clearfix"></div>
                                </div>
                            @endif
                            @if($type == 'choix')
                                <h4>Merci de préciser</h4>
                                <?php $check = 'radio'; $titre =  'Options à choix'; ?>
                                @foreach($options as $option)
                                    <div class='wrapper'>
                                        <p>{{ $option->title }} &nbsp;<div class="errorTxt"></div></p>

                                        @foreach($option->groupe as $group)
                                            <input class="options" type="{{ $check }}" required id="group_{{ $group->id }}" name="groupes[{{ $option->id }}]" value="{{ $group->id }}">
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
                <ul class="list-inline">
                    <li><button type="button" class="btn btn-default btn-lg prev-step"><i class="fa fa-arrow-left"></i>&nbsp; Précédent</button></li>
                    <li><button type="button" class="btn btn-primary btn-lg next-step">Sauver et continuer &nbsp;<i class="fa fa-arrow-right"></i></button></li>
                </ul>
            </div>

            <!-- Occurence if any -->
            @if(!$colloque->occurrences->isEmpty())
                <div class="tab-pane" role="tabpanel" id="step3">
                    <div class='wrapper'>
                        <h4>Ateliers</h4>
                        <br/><h5>Merci de préciser</h5>

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

                        <div class="clearfix"></div>
                    </div><hr/>
                    <ul class="list-inline">
                        <li><button type="button" class="btn btn-default btn-lg prev-step"><i class="fa fa-arrow-left"></i>&nbsp; Précédent</button></li>
                        <li><button type="button" class="btn btn-primary btn-lg next-step">Sauver et continuer &nbsp;<i class="fa fa-arrow-right"></i><</button></li>
                    </ul>
                </div>
            @endif

            <div class="tab-pane" role="tabpanel" id="step4">
                <h4>Vérifier l'adresse</h4>

                <?php $adresse_livraison   = $user->primary_adresse ? $user->primary_adresse : null;?>
                <?php $adresse_facturation = $user->adresse_facturation ? $user->adresse_facturation : null; ?>

                <facturation-adresse
                        :main="{{ $adresse_livraison->id }}"
                        :livraison="{{ json_encode($adresse_livraison) }}"
                        :facturation="{{ json_encode($adresse_facturation) }}">
                </facturation-adresse>

                <ul class="list-inline">
                    <li><button type="button" class="btn btn-default btn-lg prev-step">Précédent</button></li>
                    <li><button type="button" class="btn btn-primary btn-lg next-step">Sauver et continuer &nbsp;<i class="fa fa-arrow-right"></i></button></li>
                </ul>
            </div>

            <div class="tab-pane" role="tabpanel" id="complete">
                <h4>Terminer</h4>

                <p>You have successfully completed all steps.</p>

                <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                <ul class="list-inline">
                    <li><button type="button" class="btn btn-lg btn-default prev-step"><i class="fa fa-arrow-left"></i>&nbsp; Précédent</button></li>
                    <li><button type="submit" class="btn btn-lg btn-danger">Envoyer &nbsp;<i class="fa fa-check"></i></button></li>

                    <input id="submit-hidden" type="submit" style="display: none" />
                </ul>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
