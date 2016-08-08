<form data-validate-parsley action="{{ url('admin/subject') }}" enctype="multipart/form-data" method="POST" class="form">{!! csrf_field() !!}
    <div class="form-group">
        <label>Titre</label>
        <input type="title" class="form-control" placeholder="Titre">
    </div>
    <div class="form-group">
        <label class="control-label">Fichier</label>
        <input type="file" name="file">
    </div>
    <div class="form-group">
        <label class="control-label">Annexe</label>
        <input type="file" name="appendixes">
    </div>
    <div class="form-group">
        <label>Rang</label>
        <input type="rang" style="width: 100px;" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary btn-sm pull-right">Envoyer</button>
</form>