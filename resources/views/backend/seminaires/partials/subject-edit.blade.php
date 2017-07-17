<form data-validate-parsley action="{{ url('admin/subject/'.$subject->id) }}" enctype="multipart/form-data" method="POST" class="form-horizontal edit-subject-form">
    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
    
    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Titre</label>
        <div class="col-md-10 col-xs-12">
           <input type="text" name="title" class="form-control" value="{{ $subject->title }}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Fichier</label>
        <div class="col-md-10 col-xs-12">
            <button style="margin-bottom: 5px;" class="btn btn-warning btn-sm" type="button" data-toggle="collapse" data-target="#collapseFile_{{ $subject->id }}">Changer le fichier</button>
            <div style="margin-top: 5px;" class="collapse" id="collapseFile_{{ $subject->id }}">
                <input type="file" name="file">
            </div><br/>
            @if($subject->file_path)
                <p><a class="btn btn-sm btn-primary" target="_blank" href="{{ secure_asset($subject->file_path) }}"><i class="fa fa-file"></i> &nbsp;{{ $subject->file }}</a></p>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Annexes</label>
        <div class="col-md-10 col-xs-12">

            <button style="margin-bottom: 5px;" class="btn btn-warning btn-sm" type="button" data-toggle="collapse" data-target="#collapseAnnexes_{{ $subject->id }}">Ajouter des annexes</button>
            <div style="margin-top: 5px;" class="collapse" id="collapseAnnexes_{{ $subject->id }}">
                <input type="file" name="appendixes[]">
                <input type="file" name="appendixes[]">
                <input type="file" name="appendixes[]">
            </div>

            <br/>
            <div class="annexes_delete" id="delete_annexe_{{ $subject->id }}">
                @include('backend.seminaires.partials.annexes', ['subject' => $subject])
            </div>

        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Catégorie</label>
        <div class="col-md-10 col-xs-12">
            @if(!$categories->isEmpty())
                <select class="form-control" name="categories[]" multiple>
                    <option value="">Choix</option>
                    @foreach($categories as $categorie)
                        <option {{ $subject->categories->contains('id',$categorie->id) ? 'selected' : '' }} value="{{ $categorie->id }}">{{ $categorie->title }}</option>
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
                        <option {{ $subject->authors->contains('id',$author->id) ? 'selected' : '' }} value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-xs-12 control-label">Rang</label>
        <div class="col-md-10 col-xs-12">
            <input type="text" name="rang" class="form-control" style="width: 100px;" value="{{ $subject->rang }}">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 col-xs-12 text-right">
            <input type="hidden" name="id" value="{{ $subject->id }}">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </div>
</form>