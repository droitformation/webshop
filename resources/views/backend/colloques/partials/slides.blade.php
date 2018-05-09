
@if(isset($slide))

    <div class="row">
        <div class="col-md-10" ><a href="{{ $slide->getUrl() }}" class="btn btn-default btn-xs">{{ $slide->getCustomProperty('title', '') }}</a></div>
        <form class="col-md-2 text-right" action="{{ url('admin/slide/'.$slide->id) }}" method="post">
            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $colloque->id }}">
            <input type="hidden" name="media_id" value="{{ $slide->id }}">
            <button type="submit" class="btn btn-xs btn-danger">x</button>
        </form>
    </div><br/>

    <form action="{{ url('admin/slide/'.$slide->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="{{ $slide->id }}">
        <div class="form-group">
            <div class="col-sm-12">

                <p><input type="file" required name="file"></p>
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
        <p class="text-right"><button type="submit" class="btn btn-info">Editer</button></p>
    </form>
@else
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
        <p class="text-right"><button type="submit" class="btn btn-info">Ajouter</button></p>
    </form>
@endif

