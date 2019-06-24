<!-- Inscription multiple -->
<div class="invoice_for">
    <address>
        <h4><strong>Facturé à:</strong></h4>
        {{ $user->name }}<br/>
        {{ $user->adresse_facturation->adresse }}<br/>
        {{ $user->adresse_facturation->npa }} {{ $user->adresse_facturation->ville }}
    </address>
    @include('backend.inscriptions.partials.references')
</div>

<form role="form" class="validate-form" method="POST" action="{{ url('admin/inscription') }}" data-validate="parsley" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <p><a href="#" class="btn btn-sm btn-info" id="cloneBtn"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>

    <div id="wrapper_clone">
        <fieldset class="field_clone" id="fieldset_clone">

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
                @include('backend.inscriptions.partials.prices', ['select' => 'price_id[]'])
            @endif

            <!-- Occurence if any -->
            @if(!$colloque->occurrences->isEmpty())
                <h4>Conférences</h4>
                @include('backend.inscriptions..partials.occurrences', ['select' => 'occurrences[0]'])
            @endif

            @if(!$colloque->options->isEmpty())
                <h4>Merci de préciser</h4>
                @include('backend.inscriptions..partials.options', ['select' => 'groupes[0]'])
            @endif

        </fieldset>
    </div>

    <input name="user_id" value="{{ $user->id }}" type="hidden">
    <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
    <input name="type" value="{{ $type }}" type="hidden">

    <div class="clearfix"></div><br/>
    <button class="btn btn-danger" type="submit">Inscrire</button>
</form>

<!-- END Inscriptions -->