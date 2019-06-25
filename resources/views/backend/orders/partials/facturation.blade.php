<div class="form-group">
    <label class="col-sm-4 control-label">Entreprise</label>
    <div class="col-sm-7">
        <input type="text" name="company" class="form-control form-required-if" value="{{ $adresse->company }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Titre</label>
    <div class="col-sm-4">
        <select class="form-control" name="civilite_id">
            <option {{ $adresse->civilite_id == 1 ? 'selected' : '' }} value="1">Monsieur</option>
            <option {{ $adresse->civilite_id == 2 ? 'selected' : '' }} value="2">Madame</option>
            <option {{ $adresse->civilite_id == 3 ? 'selected' : '' }} value="3">(ancien Me à changer)</option>
            <option {{ $adresse->civilite_id == 4 ? 'selected' : '' }} value="4"></option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Prénom</label>
    <div class="col-sm-7">
        <input type="text" name="first_name" class="form-control form-required" value="{{ $adresse->first_name }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Nom</label>
    <div class="col-sm-7">
        <input type="text" name="last_name" class="form-control form-required" value="{{ $adresse->last_name }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Complément d'adresse</label>
    <div class="col-sm-7">
        <input type="text" name="complement" class="form-control" value="{{ $adresse->complement }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Adresse</label>
    <div class="col-sm-7">
        <input type="text" name="adresse" data-parsley-required class="form-control form-required" value="{{ $adresse->adresse }}">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">CP</label>
    <div class="col-sm-3 col-xs-6">
        <input type="text" name="cp" class="form-control" value="{{ $adresse->cp }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">NPA</label>
    <div class="col-sm-3 col-xs-6">
        <input type="text" name="npa" data-parsley-required class="form-control form-required" value="{{ $adresse->npa }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Localité</label>
    <div class="col-sm-7">
        <input type="text" name="ville" data-parsley-required class="form-control form-required" value="{{ $adresse->ville }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Canton</label>
    <div class="col-sm-7">
        @if(isset($cantons) && !$cantons->isEmpty())
            <select class="form-control" name="canton_id">
                <option value="">Choix</option>
                @foreach($cantons->pluck('title','id')->all() as $id => $c)
                    <option {{ $id == $adresse->canton_id ? 'selected' : '' }} value="{{ $id }}">{{ $c }}</option>
                @endforeach
            </select>
        @endif
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Pays</label>
    <div class="col-sm-7">
        @if(isset($pays) && !$pays->isEmpty())
            <select class="form-control" name="pays_id">
                <option value="">Choix</option>
                @foreach($pays->pluck('title','id')->all() as $id => $p)
                    <option {{ $id == $adresse->pays_id ? 'selected' : '' }} value="{{ $id }}">{{ $p }}</option>
                @endforeach
            </select>
        @endif
    </div>
</div>

<input value="{{ $adresse->user_id }}" name="user_id" type="hidden">
<input type="hidden" name="type" value="4">

