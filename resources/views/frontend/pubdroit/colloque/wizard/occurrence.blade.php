<h5>Merci de préciser</h5>

<?php $dates = $colloque->occurrences->pluck('starting_at')->unique(); ?>
<div class='wrapper'>
    @foreach($colloque->occurrences as $occurrence)
        <div class="item_wrapper">
            <input class="options occurrences" {{ $occurrence->is_active && $occurrence->is_open ? 'required' : 'disabled' }} type="{{ $dates->count() > 1 ? 'checkbox' : 'radio' }}" id="occurrence_{{ $occurrence->id }}" name="occurrences[]" required value="{{ $occurrence->id }}">
            <label for="occurrence_{{ $occurrence->id }}">
                <div class='package'>
                    <div class='name'>{{ $occurrence->title }} {{ $occurrence->full || !$occurrence->is_open ? 'COMPLET' : '' }}</div>
                    <div class='occurrence_date'>Date: {{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}</div>
                    <div class='occurrence_date occurrence_location'>{{ $occurrence->location->name }}</div>
                </div>
            </label>
        </div>
    @endforeach
</div>
