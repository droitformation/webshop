<!-- Inscription simple -->
<form role="form" class="validate-form" method="POST" action="{{ url('admin/inscription') }}" data-validate="parsley" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="_method" value="PUT">
    <fieldset>

        @if($inscription->group_id)
            <div class="form-group">
                <label>Nom du participant</label>
                <input name="participant[]" required class="form-control" value="{{ $inscription->participant->name }}" type="text">
            </div>
        @endif

        @if(!$colloque->prices->isEmpty())
            @include('colloques.partials.prices', ['select' => 'price_id', 'price_current' => $inscription->price->id])
        @endif

        @if(!$colloque->options->isEmpty())
            <h4>Merci de préciser</h4>
            @include('colloques.partials.options', ['select' => 'groupes'])
        @endif

        <input name="user_id" value="{{ $inscription->user_id }}" type="hidden">
        <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
        <input name="type" value="simple" type="hidden">

        <button class="btn btn-danger pull-right" type="submit"></button>
    </fieldset>
</form>
<!-- END Inscriptions -->