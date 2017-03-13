<h4><i class="fa fa-files-o"></i> &nbsp;Export Badges</h4>
<form action="{{ url('admin/export/badges') }}" method="POST" class="form">
    <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">{!! csrf_field() !!}

    <div class="form-group">
        <div class="col-md-2">
            <h4>Lettre de</h4>
            <select class="form-control" name="range[0]">
                <option value="">Choix</option>
                @foreach(range('a','z') as $alpha)
                    <option value="{{ $alpha }}">{{ $alpha }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <h4>Lettre à</h4>
            <select class="form-control" name="range[1]">
                <option value="">Choix</option>
                @foreach(range('a','z') as $alpha)
                    <option value="{{ $alpha }}">{{ $alpha }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <h4>Nombre de badges par page</h4>
            <div class="input-group">
                <select class="form-control" name="format">
                    @if(!empty(config('badge')))
                        <optgroup label="Etiquettes">
                            @foreach(config('badge') as $code => $config)
                                <option value="pdf|{{$code}}">{{ $config['etiquettes'] }} badges par page</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Générer</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>
</form>