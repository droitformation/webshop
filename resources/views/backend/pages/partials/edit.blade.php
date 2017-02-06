 <form class="form" method="post" action="{{ url('admin/pagecontent/'.$content->id) }}">
     <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

    <h4>Bloc</h4>

    @if($content->type != 'loi')
        <div class="form-group">
            <label for="message" class="control-label">Titre</label>
            {!! Form::text('title', $content->title , ['class' => 'form-control'] ) !!}
        </div>
    @endif

    @if($content->type == 'autorite')
        <div class="form-group">
            <label for="file" class="control-label">Image</label><br/>

            @if($content->image)
                <p><img style="max-width: 140px;" src="{{ asset($content->image) }}" alt="image"></p>
            @endif

            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal_{{ $content->id }}">Choisir un fichier</button>
            <manager :id="{{ $content->id }}" name="image" :thumbs="{{ json_encode(['products','uploads']) }}"></manager>
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
    <button type="submit" class="btn btn-primary edit-bloc-btn">&Eacute;diter</button>

</form>