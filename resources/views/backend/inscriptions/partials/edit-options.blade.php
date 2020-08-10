@if(!$colloque->options->isEmpty())

    <?php $groupe_choix = $inscription->user_options->groupBy('option_id'); ?>

    <div class="options-liste">
        <h5 class="font-weight-bold">Merci de préciser pour {{ $colloque->titre }}</h5>
        <?php $group = $colloque->options->groupBy('type'); ?>

        @foreach($group as $input => $options)

            <!-- Options checkboxes -->
            @if($input == 'checkbox')
                @foreach($options as $i => $option)
                    <?php $checked = (isset($groupe_choix) && isset($groupe_choix[$option->id])) ? 'checked' : ''; ?>
                    <div class="form-group type-choix">
                        <input type="checkbox" {{ $checked }} class="option-input" name="options[]" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}
                    </div>
                @endforeach
            @endif

            @if($input == 'text')
                @foreach($options as $i => $option)
                    <div class="form-group type-choix">
                        @foreach($options as $option)
                            <label>{{ $option->title }}</label>
                            <?php $reponse = isset($groupe_choix) && isset($groupe_choix[$option->id]) ? $groupe_choix[$option->id]->first()->reponse : ''; ?>
                            <textarea class="form-control text-input" name="options[][{{ $option->id }}]">{{ $reponse }}</textarea>
                        @endforeach
                    </div>
                @endforeach
            @endif

            <!-- Options radio -->
            @if($input == 'choix')
                @foreach($options as $option)
                    <div class="form-group group-choix type-choix">
                        <label class="control-label"><strong>{{ $option->title }}</strong></label>
                        @if(!$option->groupe->isEmpty())
                            @foreach($option->groupe as $groupe)

                                <?php
                                    $checked = '';
                                    if(isset($groupe_choix) && isset($groupe_choix[$option->id])) {
                                        $current = $groupe_choix[$option->id];
                                        $checked = ($current->contains('groupe_id', $groupe->id) ? 'checked' : '');
                                    }
                                ?>

                                <div class="radio">
                                    <label>
                                        <input type="radio" {{ $checked }} required class="group-input" name="groupes[{{ $option->id }}]" value="{{ $groupe->id }}">{{ $groupe->text }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            @endif

        @endforeach
    </div>

@endif
