<form data-validate-parsley class="form add-bloc-form">

    <h4>Bloc FAQ</h4>

    <div class="form-group">
        <label for="message" class="control-label">Titre</label>
        {!! Form::text('title', null , ['class' => 'form-control'] ) !!}
    </div>

    <div class="form-group">
        <label for="message" class="control-label">Catégorie</label>
        <select name="categorie_id" class="form-control">
            @if(!$categories->isEmpty())
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->title }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group">
        <label for="contenu" class="control-label">Contenu</label>
        {!! Form::textarea('content', null, ['class' => 'form-control redactorSimple'] ) !!}
    </div>

    <input name="type" value="faq" type="hidden">
    <input name="page_id" value="{{ $page_id }}" type="hidden">
    <button type="button" class="btn btn-green btn-sm add-bloc-btn">Ajouter</button>

</form>