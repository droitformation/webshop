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
