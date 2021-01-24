@if(isset($preview))
    <div class="mt-10 preview_handle" id="preview_rang_{{ $preview->id }}">
        <div class="row">
            <div class="col-md-6">
                <a target="_blank" href="{{ $preview->getUrl() }}" class="btn btn-default btn-xs btn-download">
                    <i class="fa fa-download"></i>&nbsp;{{ $preview->getCustomProperty('title', $preview->name) }}
                </a>
            </div>
            <form class="col-md-6 text-right" action="{{ url('admin/preview/'.$preview->id) }}" method="post">
                <a class="btn btn-info btn-xs" data-toggle="collapse" href="#preview_{{ $preview->id }}" aria-expanded="false" aria-controls="preview_">éditer</a>
                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                <input type="hidden" name="id" value="{{ $colloque->id }}">
                <input type="hidden" name="media_id" value="{{ $preview->id }}">
                <button type="submit" class="btn btn-xs btn-danger">x</button>
            </form>
        </div>

        <div class="collapse" id="preview_{{ $preview->id }}">
            <div class="well" style="margin-top: 5px;">
                <form action="{{ url('admin/preview/'.$preview->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $preview->id }}">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p><input type="file" name="file"></p>
                            <p><input type="text" class="form-control" required name="title" value="{{ $preview->getCustomProperty('title', '') }}" placeholder="Titre du fichier"></p>
                        </div>
                    </div>
                    <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                    <div class="text-right"><button type="submit" class="btn btn-info">Editer</button></div>
                </form>
            </div>
        </div>

    </div>
@else
    <h5><strong>Ajouter</strong></h5>
    <div class="well">
        <form action="{{ url('admin/preview') }}" method="post" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
            <div class="form-group">
                <div class="col-sm-12">
                    <p><input type="file" required name="file"></p>
                    <p><input type="text" class="form-control" required name="title" value="" placeholder="Titre du fichier"></p>
                </div>
            </div>
            <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
            <div class="text-right"><button type="submit" class="btn btn-info">Ajouter</button></div>
        </form>
    </div>
@endif 