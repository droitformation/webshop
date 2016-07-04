<form role="form" id="colloque-inscription" method="POST" action="{{ url('registration') }}">
    {!! csrf_field() !!}

    <h4>Prix applicable</h4>

    <div id="colloque-dependence">

        <!-- Prices -->
        <div class='wrapper'>
            <?php $prices = $colloque->prices->whereLoose('type','public'); ?>
            @if(!$prices->isEmpty())
                @foreach($prices as $price)
                    <input class="prices" required type="radio" id="price_{{ $price->id }}" name="price_id" value="{{ $price->id }}">
                    <label for="price_{{ $price->id }}">
                        <div class='package'>
                            <div class='name'>{{ $price->description }}</div>
                            <div class='price_cents'>{{ $price->price_cents }} CHF</div>
                            {!! !empty($price->remarque) ? '<hr/><p>'.$price->remarque.'</p>' : '' !!}
                        </div>
                    </label>
                @endforeach
            @endif
            <div class="clearfix"></div>
        </div>

        <hr/>

        <!-- Occurence if any -->
        @if(!$colloque->occurrences->isEmpty())
        <div class='wrapper'>
            <br/><h4>Merci de préciser</h4>

            @foreach($colloque->occurrences as $occurrence)
                <input class="options occurrences" required type="checkbox" id="occurrence_{{ $occurrence->id }}" name="occurrences[]" value="{{ $occurrence->id }}">
                <label for="occurrence_{{ $occurrence->id }}">
                    <div class='package'>
                        <div class='name'>{{ $occurrence->title }}</div>
                        <div class='occurrence_date'>Date: {{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}</div>
                        <div class='occurrence_date occurrence_location'>{{ $occurrence->location->name }}</div>
                    </div>
                </label>
            @endforeach

            <div class="clearfix"></div>
        </div><hr/>
        @endif

        <!-- Options -->
        <?php $types = $colloque->options->groupBy('type'); ?>
        @if(!$types->isEmpty())
            @foreach($types as $type => $options)

                @if($type == 'checkbox')
                    <br/><h4>Merci de préciser &nbsp;<small class="text-muted">(facultatif)</small></h4>
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

                @else
                    <br/><h4>Merci de préciser</h4>
                    <?php $check = 'radio'; $titre =  'Options à choix'; ?>
                    @foreach($options as $option)
                        <div class='wrapper'>
                            <p>{{ $option->title }} &nbsp;<div class="errorTxt"></div></p>

                            @foreach($option->groupe as $group)
                                <input class="options" type="{{ $check }}" required id="group_{{ $group->id }}" name="options[{{ $option->id }}][]" value="{{ $group->id }}">
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
