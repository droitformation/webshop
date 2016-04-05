<?php $group = $colloque->options->groupBy('type'); ?>
<?php if(isset($inscription)) { $groupe_choix = $inscription->user_options->groupBy('option_id'); } ?>

@foreach($group as $input => $options)

    <!-- Options checkboxes -->
    @if($input == 'checkbox')
        @foreach($options as $index => $option)
            <div class="form-group type-choix">
                <?php $checked = (isset($groupe_choix) && isset($groupe_choix[$option->id])) ? 'checked' : '' ?>
                @if(isset($inscription) || isset($add))
                    <input type="checkbox" {{ $checked }} class="option-input" name="options[]" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}
                @else
                    <?php $name = ($type == 'multiple' ? 'options['.$index.'][]' : 'options['.$index.']'); ?>
                    <input type="checkbox" {{ $checked }} class="option-input" name="{{ $name }}" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}
                @endif
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
                            if(isset($groupe_choix) && isset($groupe_choix[$option->id]))
                            {
                                $current = $groupe_choix[$option->id];
                                $checked = ($current->contains('groupe_id', $groupe->id) ? 'checked' : '');
                            }
                        ?>
                        <div class="radio">
                            <label>
                                <input type="radio" {{ $checked }} required class="group-input" name="{{ $select }}[{{ $option->id }}]" value="{{ $groupe->id }}">{{ $groupe->text }}
                            </label>
                        </div>
                    @endforeach
                @endif

            </div>
        @endforeach
    @endif

@endforeach