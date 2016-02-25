<!-- Inscription simple -->
<form role="form" class="validate-form" method="POST" action="{{ url('admin/inscription') }}" data-validate="parsley" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <fieldset>

        @if(!$colloque->prices->isEmpty())
            @include('backend.inscriptions.partials.prices', ['select' => 'price_id'])
        @endif

        <h4>Merci de préciser</h4>
        @if(!$colloque->options->isEmpty())
            @include('backend.inscriptions.partials.options', ['select' => 'groupes'])
        @endif

        <input name="user_id" value="{{ $user_id }}" type="hidden">
        <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
        <input name="type" value="{{ $type }}" type="hidden">

        <button class="btn btn-danger pull-right" type="submit">Inscrire</button>
    </fieldset>
</form>
<!-- END Inscriptions -->