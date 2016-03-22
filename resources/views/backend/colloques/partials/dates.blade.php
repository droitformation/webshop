<div class="form-group">
    <label class="col-sm-3 control-label">Date de début</label>
    <div class="col-sm-4">
        <input type="text" name="start_at" class="form-control datePicker required" value="{{ $colloque->start_at->format('Y-m-d') }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Date de fin</label>
    <div class="col-sm-4">
        <input type="text" name="end_at" class="form-control datePicker" value="{{ ($colloque->end_at ? $colloque->end_at->format('Y-m-d') : '') }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Délai d'inscription</label>
    <div class="col-sm-4">
        <input type="text" name="registration_at" class="form-control datePicker required" value="{{ $colloque->registration_at->format('Y-m-d') }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">
        Colloque actif jusqu'au
        <p class="help-block">Pour inscription depuis l'admin</p>
    </label>
    <div class="col-sm-4">
        <input type="text" name="active_at" class="form-control datePicker" value="{{ ($colloque->active_at ? $colloque->active_at->format('Y-m-d') : '') }}">
    </div>
</div>
