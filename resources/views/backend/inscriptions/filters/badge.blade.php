<form action="{{ url('admin/export/badges') }}" method="POST" class="form-horizontal">
    <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">{!! csrf_field() !!}
    <h4>Badges</h4>
    <select class="form-control" name="format">
        @if($badges)
            <optgroup label="Etiquettes">
                @foreach($badges as $code => $config)
                    <option value="pdf|{{$code}}">{{ $config['etiquettes'] }} badges par page</option>
                @endforeach
            </optgroup>
        @endif
    </select>
    <br/>
    <button type="submit" class="btn btn-primary" style="margin-top: 5px;"><i class="fa fa-files-o"></i> &nbsp;Générer</button>
</form>