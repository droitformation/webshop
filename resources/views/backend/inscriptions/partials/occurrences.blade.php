<?php if(isset($inscription)) { $occurrences_choix = $inscription->occurrences->lists('id')->all(); } ?>

@foreach($colloque->occurrences as $index => $occurrence)
    <div class="form-group type-choix">
        <?php $name = (isset($type) && $type == 'multiple' ? 'occurrences[0][]' : 'occurrences['.$index.']'); ?>
        <input type="checkbox" {{ isset($occurrences_choix) && in_array($occurrence->id ,$occurrences_choix) ? 'checked' : '' }} class="occurrence-input" name="{{ $name }}" value="{{ $occurrence->id }}" />
        {{ $occurrence->title }}
        Date: {{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}
    </div>
@endforeach