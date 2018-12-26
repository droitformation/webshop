@if(isset($bloc->colloque))

    <h3 class="title">{{ $bloc->colloque->titre }}</h3>
    <p>{!! $bloc->colloque->soustitre !!}</p>
    <p><strong>Organisé par: </strong><cite>{{ $bloc->colloque->organisateur }}</cite></p>
    <img width="130" border="0" alt="{{ $bloc->colloque->titre }}" src="{{ secure_asset($bloc->colloque->frontend_illustration) }}" />

@endif