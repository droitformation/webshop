<!-- Inscription simple -->
<div class="clearfix"></div><hr>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<fieldset>

    <div class="invoice_for">
        <address>
            <h4><strong>Facturé à:</strong></h4>
            {{ $user->name }}<br/>
            {{ $user->adresse_facturation->adresse }}<br/>
            {{ $user->adresse_facturation->npa }} {{ $user->adresse_facturation->ville }}
        </address>
        @include('backend.inscriptions.partials.references')
    </div>

    <!-- Occurrences if any -->
    @if(!$colloque->occurrences->isEmpty())
        <h4>Merci de préciser</h4>
        @include('backend.inscriptions..partials.occurrences', ['select' => 'occurrences[0]'])
    @endif

    @if(!$colloque->prices->isEmpty() || !$colloque->price_link->isEmpty())
        @include('backend.inscriptions.partials.prices')
    @endif

    @if(!$colloque->options->isEmpty())
        <h4>Merci de préciser</h4>
        @include('backend.inscriptions.partials.options', ['select' => 'groupes'])
    @endif

    <option-link colloque="{{ $colloque->id }}" :prices="{{ $colloque->price_display }}" :pricelinks="{{ $colloque->price_link_display }}"></option-link>

    <input name="user_id" value="{{ $user->id }}" type="hidden">
    <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
    <input name="type" value="{{ $type }}" type="hidden">

    <div class="form-group">
        <br><button id="makeInscription" class="btn btn-danger pull-right" type="submit">Inscrire</button>
    </div>
</fieldset>
<!-- END Inscriptions -->