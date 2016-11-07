<h4><i class="fa fa-download"></i> &nbsp;Export excel</h4>
<form action="{{ url('admin/export/inscription') }}" method="POST" class="form">
    <input type="hidden" name="id" value="{{ $colloque->id }}">{!! csrf_field() !!}
    <div class="row">
        <div class="form-group col-md-6">
            <h4>Infos</h4>
            @if(!empty($names))
                @foreach($names as $key => $name)
                    <div class="checkbox-inline checkbox-border">
                        <label><input class="checkbox_all" value="{{ $name }}" checked name="columns[{{ $key }}]" type="checkbox"> {{ $name }}</label>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="form-group col-md-3">
            <h4>Tri par options</h4>
            <div class="radio"><label><input type="radio" name="sort" value="" checked> Normal</label></div>
            <div class="radio"><label><input type="radio" name="sort" value="1"> Par options à choix</label></div>
        </div>
        @if(count($colloque->occurrences))
            <div class="form-group col-md-3">
                <h4>Tri par conférence</h4>
                <select class="form-control" name="occurrence[]">
                    <option value="">Toutes les conférences/Salles</option>
                    @foreach($colloque->occurrences as $occurrence)
                        <option value="{{ $occurrence->id }}">{{ $occurrence->title }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-inverse">Exporter</button>
    </div>
</form>