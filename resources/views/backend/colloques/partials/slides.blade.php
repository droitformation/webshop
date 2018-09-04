@if(isset($slide))
    <div class="mt-10 slide_handle" id="slide_rang_{{ $slide->id }}">
        <div class="row">
            <div class="col-md-6">
                <a target="_blank" href="{{ $slide->getUrl() }}" class="btn btn-default btn-xs btn-download">
                    <i class="fa fa-download"></i>&nbsp;{{ $slide->getCustomProperty('title', $slide->name) }}
                </a>
            </div>
            <div class="col-md-3">
                {{ formatPeriod($slide->getCustomProperty('start_at', ''),$slide->getCustomProperty('end_at', '')) }}
            </div>
            <form class="col-md-3 text-right" action="{{ url('admin/slide/'.$slide->id) }}" method="post">
                <a class="btn btn-info btn-xs" data-toggle="collapse" href="#slide_{{ $slide->id }}" aria-expanded="false" aria-controls="slide_">éditer</a>
                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                <input type="hidden" name="id" value="{{ $colloque->id }}">
                <input type="hidden" name="media_id" value="{{ $slide->id }}">
                <button type="submit" class="btn btn-xs btn-danger">x</button>
            </form>
        </div>

        <div class="collapse" id="slide_{{ $slide->id }}">
            <div class="well" style="margin-top: 5px;">
                <form action="{{ url('admin/slide/'.$slide->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $slide->id }}">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p><input type="file" name="file"></p>
                            <p><input type="text" class="form-control" required name="title" value="{{ $slide->getCustomProperty('title', '') }}" placeholder="Titre du fichier"></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control datePicker" required name="start_at" value="{{ $slide->getCustomProperty('start_at', '')  }}" placeholder="Date début">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datePicker" required name="end_at" value="{{ $slide->getCustomProperty('end_at', '') }}" placeholder="Date fin">
                                </div>
                            </div>
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
        <form action="{{ url('admin/slide') }}" method="post" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
            <div class="form-group">
                <div class="col-sm-12">
                    <p><input type="file" required name="file"></p>
                    <p><input type="text" class="form-control" required name="title" value="" placeholder="Titre du fichier"></p>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control datePicker" id="start_at" name="start_at" value="" placeholder="Date début">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control datePicker" id="end_at" name="end_at" value="" placeholder="Date fin">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
            <div class="text-right"><button type="submit" class="btn btn-info">Ajouter</button></div>
        </form>
    </div>
@endif