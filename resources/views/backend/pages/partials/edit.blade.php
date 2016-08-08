<form data-validate-parsley class="form edit-bloc-form" data-id="{{ $content->id }}">

    <h4>Bloc</h4>

    @if($content->type != 'loi')
        <div class="form-group">
            <label for="message" class="control-label">Titre</label>
            {!! Form::text('title', $content->title , ['class' => 'form-control'] ) !!}
        </div>
    @endif

    @if($content->type == 'autorite')
        <div class="form-group">
            <label for="file" class="control-label">Image</label>
            <img src="{{ asset($content->image) }}" alt="image">
            <div class="file-upload-wrapper" data-name="file">
                <button type="button" class="btn btn-default" id="image" data-toggle="modal" data-target="#uploadModal">Chercher</button>
                <div class="file-input"></div>

                @include('manager.modal')
            </div>
        </div>
    @endif

    @if($content->type == 'faq')
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
    @endif

    @if($content->type == 'text')
        <div class="form-group">
            <label for="message" class="control-label">Style de bloc</label>
            <select class="form-control" name="style">
                <option value="">Choisir</option>
                <option {{ $content->style == 'agenda' ? 'selected' : '' }} value="agenda">Bloc agenda rouge</option>
            </select>
        </div>
    @endif

    <div class="form-group">
        <label for="contenu" class="control-label">Contenu</label>
        {!! Form::textarea('content', $content->content, ['class' => 'form-control redactorSimple'] ) !!}
    </div>

    <input name="id" value="{{ $content->id }}" type="hidden">
    <input name="type" value="{{ $content->type }}" type="hidden">
    <input name="page_id" value="{{ $content->page_id }}" type="hidden">
    <button type="button" class="btn btn-primary edit-bloc-btn">&Eacute;diter</button>

</form>