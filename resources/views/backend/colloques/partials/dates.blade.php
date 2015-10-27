<div class="form-group">
    <label class="col-sm-3 control-label">Date de début</label>
    <div class="col-sm-3">
        <input type="text" name="start_at" class="form-control datePicker required" value="{{ $colloque->start_at->format('Y-m-d') }}" id="start_at">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Date de fin</label>
    <div class="col-sm-3">
        <input type="text" name="end_at" class="form-control datePicker" value="{{ ($colloque->end_at ? $colloque->end_at->format('Y-m-d') : '') }}" id="end_at">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Délai d'inscription</label>
    <div class="col-sm-3">
        <input type="text" name="registration_at" class="form-control datePicker" value="{{ $colloque->registration_at->format('Y-m-d') }}" id="registration_at">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Colloque actif jusqu'au</label>
    <div class="col-sm-3">
        <input type="text" name="active_at" class="form-control datePicker" value="{{ ($colloque->active_at ? $colloque->active_at->format('Y-m-d') : '') }}" id="active_at">
    </div>
    <div class="col-sm-3">
        <p class="help-block">Garder le colloque actif dans la liste pour inscrire des personnes depuis l'admin</p>
    </div>
</div>
