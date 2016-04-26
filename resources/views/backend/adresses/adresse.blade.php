<form action="{{ url('admin/adresse/'.$adresse->id) }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    {!! csrf_field() !!}
    <hr/>
    @if($adresse->user_id > 0)
        <div class="form-group">
            <label class="col-sm-4 control-label">Type d'adresse</label>
            <div class="col-sm-7">
                <select class="form-control" name="type">
                    <option {{ $adresse->type == 1 ? 'checked' : '' }} value="1">Contact</option>
                    <option {{ $adresse->type == 2 ? 'checked' : '' }} value="2">Privé</option>
                    <option {{ $adresse->type == 3 ? 'checked' : '' }} value="3">Professionnelle</option>
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="type" value="1">
    @endif
    <div class="form-group">
        <label class="col-sm-4 control-label">Entreprise</label>
        <div class="col-sm-7">
            <input type="text" name="company" class="form-control" value="{{ $adresse->company }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Titre</label>
        <div class="col-sm-4">
            <select class="form-control" name="civilite_id">
                <option {{ $adresse->civilite_id == 1 ? 'selected' : '' }} value="1">Monsieur</option>
                <option {{ $adresse->civilite_id == 2 ? 'selected' : '' }} value="2">Madame</option>
                <option {{ $adresse->civilite_id == 3 ? 'selected' : '' }} value="3">Me</option>
                <option {{ $adresse->civilite_id == 4 ? 'selected' : '' }} value="4"></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Prénom</label>
        <div class="col-sm-7">
            <input type="text" name="first_name" data-parsley-required class="form-control form-required" value="{{ $adresse->first_name }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Nom</label>
        <div class="col-sm-7">
            <input type="text" name="last_name" data-parsley-required class="form-control form-required" value="{{ $adresse->last_name }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Email</label>
        <div class="col-sm-7">
            <input type="text" name="email" class="form-control" value="{{ $adresse->email }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Adresse</label>
        <div class="col-sm-7">
            <input type="text" name="adresse" data-parsley-required class="form-control form-required" value="{{ $adresse->adresse }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Complément d'adresse</label>
        <div class="col-sm-7">
            <input type="text" name="complement" class="form-control" value="{{ $adresse->complement }}">
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
            {!! Form::select('canton_id', $cantons->lists('title','id')->all() , $adresse->canton_id, ['data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Choix']) !!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Pays</label>
        <div class="col-sm-7">
            {!! Form::select('pays_id', $pays->lists('title','id')->all() , $adresse->pays_id, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Choix']) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Profession</label>
        <div class="col-sm-7">
            {!! Form::select('profession_id', $professions->lists('title','id')->all() , $adresse->profession_id, ['class' => 'form-control', 'placeholder' => 'Choix']) !!}
        </div>
    </div>

    {!! Form::hidden('id', $adresse->id) !!}
    <br/>
    <p class="pull-right"><button class="btn btn-primary" type="submit">Enregistrer</button></p>
</form>