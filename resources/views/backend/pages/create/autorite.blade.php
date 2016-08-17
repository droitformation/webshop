<form data-validate-parsley class="form add-bloc-form">

    <h4>Bloc autorité</h4>

    <div class="form-group">
        <label for="message" class="control-label">Titre</label>
        {!! Form::text('title', null , ['class' => 'form-control'] ) !!}
    </div>

    <div class="form-group">
        <label for="file" class="control-label">Image</label>
        <div class="file-upload-wrapper" data-name="file">
            <button type="button" class="btn btn-default" id="image" data-toggle="modal" data-target="#uploadModal">Chercher</button>
            <div class="file-input"></div>

            @include('manager.modal')
        </div>
    </div>

    <div class="form-group">
        <label for="contenu" class="control-label">Contenu</label>
        {!! Form::textarea('content', null, ['class' => 'form-control redactorSimple'] ) !!}
    </div>

    <input name="type" value="autorite" type="hidden">
    <input name="page_id" value="{{ $page_id }}" type="hidden">
    <button type="button" class="btn btn-magenta btn-sm add-bloc-btn">Ajouter</button>

</form>