<form action="{{ url('admin/export/inscription') }}" method="POST" class="form-horizontal">
    <div class="row">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="{{ $colloque->id }}">{!! csrf_field() !!}
        <div class="col-md-6">
            <h4>Infos</h4>
            <p><input type="checkbox" id="select_all" /> &nbsp;<span class="text-primary">Séléctionner tous</span></p>
            @if(!empty($names))
                @foreach($names as $key => $name)
                    <div class="checkbox-inline checkbox-border">
                        <label><input class="checkbox_all" value="{{ $name }}" name="columns[{{ $key }}]" type="checkbox"> {{ $name }}</label>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="col-md-6" style="border-right: 1px solid #e6e7e8; padding-right: 15px;">
            <h4 style="margin-bottom: 0;">Tri</h4>

            <div class="form-group">
                <div class="radio"><label><input type="radio" name="sort" value="" checked> Normal</label></div>
                <div class="radio"><label><input type="radio" name="sort" value="1"> Par options</label></div>
            </div>

            @if(count($colloque->occurrences))
                <div class="form-group" style="max-width: 300px;">
                    <select class="form-control" name="occurrence[]">
                        <option>Par conférence</option>
                        @foreach($colloque->occurrences as $occurrence)
                            <option value="{{ $occurrence->id }}">{{ $occurrence->title }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-sm btn-inverse" style="margin-top: 5px;"><i class="fa fa-download"></i> &nbsp;Exporter liste excel</button>
                <a href="{{ url('admin/export/qrcodes/'.$colloque->id) }}" class="btn btn-sm btn-brown" style="margin-top: 5px;">
                    <i class="fa fa-qrcode"></i> &nbsp;Exporter qrcodes
                </a>
            </div>
        </div>
    </div>
</form>