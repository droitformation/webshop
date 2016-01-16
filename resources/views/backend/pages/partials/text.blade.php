<form data-validate-parsley class="form add-bloc-form">

    <h4>Bloc texte</h4>

    <div class="form-group">
        <label for="message" class="control-label">Titre</label>
        {!! Form::text('title', null , ['class' => 'form-control'] ) !!}
    </div>

    <div class="form-group">
        <label for="message" class="control-label">Style de bloc</label>
        <select class="form-control" name="style">
            <option value="">Choisir</option>
            <option value="agenda">Bloc agenda rouge</option>
        </select>
    </div>

    <div class="form-group">
        <label for="contenu" class="control-label">Contenu</label>
        {!! Form::textarea('content', null, ['class' => 'form-control redactorBlocSimple'] ) !!}
    </div>

    <input name="type" value="text" type="hidden">
    <input name="page_id" value="{{ $page_id }}" type="hidden">
    <button type="button" class="btn btn-magenta btn-sm add-bloc-btn">Ajouter</button>

</form>