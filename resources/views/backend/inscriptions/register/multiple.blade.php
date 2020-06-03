<!-- Inscription multiple -->
<div class="invoice_for">
    <address>
        <h4><strong>Facturé à:</strong></h4>
        {{ $user->name }}<br/>
        {{ $user->adresse_facturation->adresse }}<br/>
        {{ $user->adresse_facturation->npa }} {{ $user->adresse_facturation->ville }}
    </address>
    @include('backend.inscriptions.partials.references')
    <div class="invoice_rabais">

        <h4><strong>Choix du rabais</strong></h4>
        <div class="form-group">
            <!-- Only public prices -->
            <select name="rabais_id" class="form-control">
                <option value="">Choix</option>
                @foreach($rabais as $rabai)
                    <option value="{{ $rabai->id }}">{{ $rabai->value }} CHF</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<input name="user_id" value="{{ $user->id }}" type="hidden">
<input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
<input name="type" value="{{ $type }}" type="hidden">

{{--<participant
        form="multiple"
        :colloque="{{ $colloque }}"
        :prices="{{ $colloque->price_display }}"
        :pricelinks="{{ $colloque->price_link_display }}"></participant>--}}

<p><a href="#" class="btn btn-sm btn-info" id="cloneBtn"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>
<div id="wrapper_clone">
    <fieldset class="field_clone" id="fieldset_clone" data-index="0">

        <div class="form-group">
            <label>Nom du participant</label>
            <input name="participant[]" required class="form-control participant-input" value="" type="text">
            <p class="text-muted">Inscrire "prenom, nom"</p>
        </div>

        <div class="form-group">
            <label>Email (lier à un compte)</label>
            <input name="email[]" class="form-control" value="" type="text">
        </div>

        @if(!$colloque->prices->isEmpty())
            @include('backend.inscriptions.partials.prices', ['select' => 'price_id[]', 'form' => 'multiple'])
        @endif

        <!-- Occurence if any -->
        @if(!$colloque->occurrences->isEmpty())
            <h4>Conférences</h4>
            @include('backend.inscriptions.partials.occurrences', ['select' => 'occurrences[0]'])
        @endif

        @if(!$colloque->options->isEmpty())
            <h4>Merci de préciser</h4>
            @include('backend.inscriptions.partials.options', ['select' => 'groupes[0]'])
        @endif

    </fieldset>
</div>

<div class="form-group">
    <br><button id="makeInscription" class="btn btn-danger pull-right" type="submit">Inscrire</button>
</div>
<!-- END Inscriptions -->