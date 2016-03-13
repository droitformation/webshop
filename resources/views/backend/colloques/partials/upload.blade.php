<form action="{{ url('admin/uploadFile') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
    {!! csrf_field() !!}
    <div class="form-group">
        <div class="col-sm-10">
            <div class="form-group">
                <input type="file" name="file">
                <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                <input type="hidden" name="path" value="files/colloques">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="name" value="{{ $name }}">
            </div>
        </div>
        <div class="col-sm-2 text-right">
            <button type="submit" class="btn btn-info">Ajouter</button>
        </div>
    </div>
</form>