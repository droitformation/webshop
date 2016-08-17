<form data-validate-parsley class="form add-bloc-form">

    <h4>Bloc lien</h4>

    <div class="form-group">
        <label for="message" class="control-label">Titre</label>
        {!! Form::text('title', null , ['class' => 'form-control'] ) !!}
    </div>

    <div class="form-group">
        <label for="contenu" class="control-label">Contenu</label>
        {!! Form::textarea('content', null, ['class' => 'form-control redactorSimple'] ) !!}
    </div>

    <input name="type" value="lien" type="hidden">
    <input name="page_id" value="{{ $page_id }}" type="hidden">
    <button type="button" class="btn btn-orange btn-sm add-bloc-btn">Ajouter</button>

</form>