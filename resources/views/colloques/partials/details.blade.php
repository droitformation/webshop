<?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>

<div class="row">
    <div class="col-md-5 colloque-details">

        <p><strong>Lieu:</strong></p>
        <p>{{ $colloque->location->name }}, {{ $colloque->location->adresse }}</p>

        <p><strong>Date:</strong></p>
        <p>{{ $colloque->event_date }}</p>

        <p><strong>Délai d'inscription:</strong></p>
        <p>{{ $colloque->registration_at->formatLocalized('%d %B %Y') }}</p>

        <p><strong>Prix d'inscription:</strong></p>

        @if(!$colloque->prices->isEmpty())
            <dl>
                @foreach($colloque->prices as $price)
                    <dt>{{ $price->description }}</dt>
                    <dd>{{ $price->price_cents }} CHF</dd>
                @endforeach
            </dl>
        @endif

    </div>
    <div class="col-md-3">
        @if(!$colloque->documents->isEmpty())
            @foreach($colloque->documents as $document)
                <p>{{ $document->price_cents }}</p>
            @endforeach
        @endif
    </div>
    <div class="col-md-4">

    </div>
</div>


<?php

/*    echo '<pre>';
    print_r($colloque->prices);
    echo '</pre>';*/

?>
