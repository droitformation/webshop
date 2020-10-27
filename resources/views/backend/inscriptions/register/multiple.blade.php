<!-- Inscription multiple -->
<participant
        form="multiple"
        user_id="{{ $user->id }}"
        _token="{{ csrf_token() }}"
        :colloque="{{ $colloque }}"
        :prices="{{ $colloque->price_display }}"
        :pricelinks="{{ $colloque->price_link_display }}"></participant>

{{--<p><a href="#" class="btn btn-sm btn-info" id="cloneBtn"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>
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

        <!-- Occurrence if any -->
        @if(!$colloque->occurrences->isEmpty())
            <h4>Conférences</h4>
            @include('backend.inscriptions.partials.occurrences', ['select' => 'occurrences[0]'])
        @endif

        @include('backend.inscriptions.partials.options', ['select' => 'groupes[0]', 'colloque' => $colloque, 'index' => 0])

        <div class="options-liste-box"></div>

    </fieldset>
</div>

<div class="form-group">
    <br><button id="makeInscription" class="btn btn-danger pull-right" type="submit">Inscrire</button>
</div>--}}
<!-- END Inscriptions -->