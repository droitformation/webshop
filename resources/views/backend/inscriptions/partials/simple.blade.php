<!-- Inscription simple -->
<div class="clearfix"></div><hr>
<form role="form" class="validate-form" method="POST" action="{{ url('admin/inscription') }}" data-validate="parsley" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <fieldset>

        @if(!$colloque->prices->isEmpty())
            @include('backend.inscriptions.partials.prices', ['select' => 'price_id'])
        @endif

        <!-- Occurence if any -->
        @if(!$colloque->occurrences->isEmpty())
            <h4>Merci de préciser</h4>
            @include('backend.inscriptions..partials.occurrences', ['select' => 'occurrences[0]'])
        @endif

        @if(!$colloque->options->isEmpty())
            <h4>Merci de préciser</h4>
            @include('backend.inscriptions.partials.options', ['select' => 'groupes'])
        @endif

        <input name="user_id" value="{{ $user_id }}" type="hidden">
        <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
        <input name="type" value="{{ $type }}" type="hidden">

        <div class="form-group">
            <br><button class="btn btn-danger pull-right" type="submit">Inscrire</button>
        </div>
    </fieldset>
</form>
<!-- END Inscriptions -->