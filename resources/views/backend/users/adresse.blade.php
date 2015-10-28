<form action="{{ url('admin/adresse/'.$adresse->id) }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    {!! csrf_field() !!}
    <hr/>
    <div class="form-group">
        <label class="col-sm-4 control-label">Adresse de livraison</label>
        <div class="col-sm-7">
            <label class="radio-inline">
                <input type="radio" {{ $adresse->livraison ? 'checked' : '' }} name="livraison" value="1"> Oui
            </label>
            <label class="radio-inline">
                <input type="radio" {{ !$adresse->livraison ? 'checked' : '' }} name="livraison" value="0"> Non
            </label>
        </div>
    </div>
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
    <div class="form-group">
        <label class="col-sm-4 control-label">Entreprise</label>
        <div class="col-sm-7">
            <input type="text" name="company" class="form-control" value="{{ $adresse->company }}" placeholder="Entreprise">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Titre</label>
        <div class="col-sm-7">
            <label class="radio-inline">
                &nbsp;<input type="radio" data-parsley-required name="civilite" {{ $adresse->civilite_id == 1 ? 'checked' : ''}} value="1"> Monsieur&nbsp;
            </label>
            <label class="radio-inline">
                &nbsp;<input type="radio" data-parsley-required name="civilite" {{ $adresse->civilite_id == 2 ? 'checked' : ''}} value="2"> Madame&nbsp;
            </label>
            <label class="radio-inline">
                <input type="radio" data-parsley-required name="civilite" {{ $adresse->civilite_id == 3 ? 'checked' : ''}} value="3"> Me
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Prénom</label>
        <div class="col-sm-7">
            <input type="text" name="first_name" data-parsley-required class="form-control form-required" value="{{ $adresse->first_name }}" placeholder="Prénom">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Nom</label>
        <div class="col-sm-7">
            <input type="text" name="last_name" data-parsley-required class="form-control form-required" value="{{ $adresse->last_name }}" placeholder="Nom">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Adresse</label>
        <div class="col-sm-7">
            <input type="text" name="adresse" data-parsley-required class="form-control form-required" value="{{ $adresse->adresse }}" placeholder="Adresse">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Complément d'adresse</label>
        <div class="col-sm-7">
            <input type="text" name="complement" class="form-control" value="{{ $adresse->complement }}" placeholder="Complément d'adresse">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Case Postale</label>
        <div class="col-sm-3 col-xs-6">
            <input type="text" name="cp" class="form-control" value="{{ $adresse->cp }}" placeholder="Case Postale">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Code postal</label>
        <div class="col-sm-3 col-xs-6">
            <input type="text" name="npa" data-parsley-required class="form-control form-required" value="{{ $adresse->npa }}" placeholder="Code postal">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Localité</label>
        <div class="col-sm-7">
            <input type="text" name="ville" data-parsley-required class="form-control form-required" value="{{ $adresse->ville }}" placeholder="Localité">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Canton</label>
        <div class="col-sm-7">
            {!! Form::select('canton_id', $cantons->lists('title','id')->all() , $adresse->canton_id, ['data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Canton']) !!}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Pays</label>
        <div class="col-sm-7">
            {!! Form::select('pays_id', $pays->lists('title','id')->all() , $adresse->pays_id, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Pays']) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Profession</label>
        <div class="col-sm-7">
            {!! Form::select('profession_id', $professions->lists('title','id')->all() , $adresse->profession_id, ['class' => 'form-control form-required', 'placeholder' => 'Profession_id']) !!}
        </div>
    </div>

    {!! Form::hidden('id', $adresse->id) !!}
    {!! Form::hidden('user_id', $user->id) !!}
    <br/>
    <p class="pull-right"><button class="btn btn-primary" type="submit">Enregistrer</button></p>
</form>