<form data-validate-parsley class="form-horizontal">

    <h4>Bloc lois</h4>

    <div class="form-group">
        <label for="contenu" class="col-sm-2 control-label">Contenu</label>
        <div class="col-sm-9">
            {!! Form::textarea('content', null, ['class' => 'form-control redactorBlocSimple'] ) !!}
        </div>
    </div>

    <input name="type" value="loi" type="hidden">
    <input name="page_id" value="{{ $page_id }}" type="hidden">

</form>