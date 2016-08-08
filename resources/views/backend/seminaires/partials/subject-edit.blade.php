<form data-validate-parsley action="{{ url('admin/subject/'.$subject->id) }}" enctype="multipart/form-data" method="POST" class="form">
    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
    <div class="form-group">
        <label>Titre</label>
        <input type="title" class="form-control" value="{{ $subject->title }}">
    </div>

    <div class="form-group">
        <label class="control-label">Fichier</label>
        <input type="file" name="file">
        @if($subject->file_path)
            <p><a class="text-primary" href="{{ asset($subject->file_path) }}">{{ $subject->file_path }}</a></p>
        @endif
    </div>
    <div class="form-group">
        <label class="control-label">Annexe</label>
        <input type="file" name="appendixes">
        @if($subject->annexe_path)
            <p><a class="text-primary" href="{{ asset($subject->annexe_path) }}">{{ $subject->annexe_path }}</a></p>
        @endif
    </div>

    <div class="form-group">
        <label>Catégorie</label>
        @if(!$categories->isEmpty())
            <select class="form-control" name="categorie_id[]" multiple>
                <option value="">Choix</option>
                @foreach($categories as $categorie)
                    <option {{ $subject->categories->contains('id',$categorie->id) ? 'selected' : '' }} value="{{ $categorie->id }}">{{ $categorie->title }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div class="form-group">
        <label>Rang</label>
        <input type="rang" class="form-control" style="width: 100px;" value="{{ $subject->rang }}">
    </div>
    <button type="submit" class="btn btn-primary btn-sm pull-right">Envoyer</button>
</form>