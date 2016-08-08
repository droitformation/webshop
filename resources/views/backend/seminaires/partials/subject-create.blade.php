<form data-validate-parsley action="{{ url('admin/subject') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">{!! csrf_field() !!}
    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Titre</label>
        <div class="col-md-10 col-xs-12">
            <input type="text" class="form-control" name="title">
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Fichier</label>
        <div class="col-md-10 col-xs-12">
            <input type="file" name="file">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Annexe</label>
        <div class="col-md-10 col-xs-12">
            <input type="file" name="appendixes">
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Catégorie</label>
        <div class="col-md-10 col-xs-12">
            @if(!$categories->isEmpty())
                <select class="form-control" name="categories[]" multiple>
                    <option value="">Choix</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->title }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Auteur</label>
        <div class="col-md-10 col-xs-12">
            @if(!$authors->isEmpty())
                <select class="form-control" name="authors[]">
                    <option value="">Choix</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Rang</label>
        <div class="col-md-10 col-xs-12">
            <input type="text" name="rang" class="form-control" style="width: 100px;" value="{{ isset($max) ? $max + 1 : 0 }}">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 col-xs-12 text-right">
            <input type="hidden" name="seminaire_id" value="{{ $seminaire->id }}">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </div>

</form>