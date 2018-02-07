<h4><i class="fa fa-download"></i> &nbsp;Export excel</h4>
<form action="{{ url('admin/export/inscription') }}" method="POST" class="form">
    <input type="hidden" name="id" value="{{ $colloque->id }}">{!! csrf_field() !!}
    <div class="row">
        <div class="form-group col-md-6">
            <h4>Choix des information</h4>
            @if(!empty($names))
                <p><label><input type="checkbox" id="select_all"> Tout séléctionner</label></p>
                @foreach($names as $key => $name)
                    <div class="checkbox-inline checkbox-border">
                        <label><input class="checkbox_all" value="{{ $name }}" checked name="columns[{{ $key }}]" type="checkbox"> {{ $name }}</label>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="form-group col-md-3">
            <h4>Options</h4>
            <div class="radio"><label><input type="radio" name="sort" value="" checked> Pas de tri</label></div>
            <div class="radio"><label><input type="radio" name="sort" value="checkbox"> Trier par options</label></div>
            <div class="radio"><label><input type="radio" name="sort" value="choice"> Trier par choix</label></div>
        </div>
        @if(count($colloque->occurrences))
            <div class="form-group col-md-3">
                <h4>Conférences</h4>
                <div class="checkbox"><label><input type="checkbox" name="dispatch" value="1" checked> Trier par conférences/salles</label></div>
                <p>- ou -</p>
                <select class="form-control" name="occurrence[]">
                    <option value="">Choix d'une salle/conférence</option>
                    @foreach($colloque->occurrences as $occurrence)
                        <option value="{{ $occurrence->id }}">Que {{ $occurrence->title }}</option>
                    @endforeach
                </select><br/>
            </div>
        @endif
    </div>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-inverse">Exporter</button>
    </div>
</form>