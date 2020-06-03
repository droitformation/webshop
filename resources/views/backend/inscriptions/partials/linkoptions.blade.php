<div class="options-liste-multiple">

    @foreach($colloques as $colloque)

        <h4>Merci de préciser pour {{ $colloque->titre }}</h4>
        <?php $group = $colloque->options->groupBy('type'); ?>

        @foreach($group as $input => $options)

            <!-- Options checkboxes -->
            @if($input == 'checkbox')
                @foreach($options as $i => $option)
                    <div class="form-group type-choix">
                        <input type="checkbox" class="option-input" name="colloque[{{ $colloque->id }}][options][{{ $index }}][]" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}
{{--
                        <input type="checkbox" class="option-input" name="colloque[{{ $colloque->id }}][options][{{ $index }}][{{ $i }}]" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}
--}}
                    </div>
                @endforeach
            @endif

            @if($input == 'text')
                @foreach($options as $i => $option)
                    <div class="form-group type-choix">
                        @foreach($options as $option)
                            <label>{{ $option->title }}</label>
                            <textarea class="form-control text-input" name="colloque[{{ $colloque->id }}][options][{{ $index }}][{{ $option->id }}][]"></textarea>
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
                                <div class="radio">
                                    <label>
                                        <input type="radio" required class="group-input" name="colloque[{{ $colloque->id }}][groupes][{{ $index }}][{{ $option->id }}]" value="{{ $groupe->id }}">{{ $groupe->text }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            @endif

        @endforeach
    @endforeach
</div>


