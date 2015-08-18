<?php $group = $colloque->options->groupBy('type'); ?>
@foreach($group as $type => $options)

    <!-- Options checkboxes -->
    @if($type == 'checkbox')
        @foreach($options as $option)
            <div class="form-group type-choix"><input type="checkbox" name="options[]" value="{{ $option->id }}" /> &nbsp;{{ $option->title }}</div>
        @endforeach
    @endif

    <!-- Options radio -->
    @if($type == 'choix')
        @foreach($options as $option)
            <div class="form-group group-choix type-choix">
                <label class="control-label"><strong>{{ $option->title }}</strong></label>
                <?php $option->load('groupe'); ?>
                @if(!$option->groupe->isEmpty())
                    @foreach($option->groupe as $groupe)
                        <div class="radio">
                            <label>
                                <input type="radio" required name="{{ $select }}[{{ $option->id }}]" value="{{ $groupe->id }}">
                                {{ $groupe->text }}
                            </label>
                        </div>
                    @endforeach
                @endif

            </div>
        @endforeach
    @endif

@endforeach