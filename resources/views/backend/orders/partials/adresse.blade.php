<div class="col-md-6">
    <div class="form-group ">
        <label class="control-label">Titre</label>
        {!! Form::select('adresse[civilite_id]', [4 => '',1 => 'Monsieur',2 => 'Madame',3 => 'Me'] , old('adresse.civilite_id', Session::get('adresse.civilite_id')) , ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label"><span class="text-info">Entreprise</span></label>
        <input type="text" name="adresse[company]" class="form-control form-required" value="{{ old('adresse.company', Session::get('adresse.company')) }}" placeholder="Entreprise">
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="control-label"><span class="text-info">Prénom</span></label>
            <input type="text" name="adresse[first_name]" class="form-control form-required" value="{{ old('adresse.first_name', Session::get('adresse.first_name')) }}" placeholder="Prénom">
        </div>
        <div class="col-md-6">
            <label class="control-label"><span class="text-info">Nom</span></label>
            <input type="text" name="adresse[last_name]" class="form-control form-required" value="{{ old('adresse.last_name', Session::get('adresse.last_name')) }}" placeholder="Nom">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="control-label"><span class="text-danger">E-mail</span></label>
            <input type="email" name="adresse[email]" data-parsley-required class="form-control form-required" value="{{ old('adresse.email', Session::get('adresse.email')) }}" placeholder="E-mail">
        </div>
        <div class="col-md-6">
            <label class="control-label"><span class="text-danger">Mot de passe</span></label>
            <input type="password" name="adresse[password]" data-parsley-required class="form-control form-required" value="{{ old('adresse.password') }}">
        </div>
    </div>

</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="control-label"><span class="text-danger">Adresse</span></label>
        <input type="text" name="adresse[adresse]" data-parsley-required class="form-control form-required" value="{{ old('adresse.adresse', Session::get('adresse.adresse')) }}" placeholder="Adresse">
    </div>
    <div class="form-group row">
        <div class="col-md-8">
            <label class="control-label">Complément d'adresse</label>
            <input type="text" name="adresse[complement]" class="form-control" value="{{ old('adresse.complement', Session::get('adresse.complement')) }}" placeholder="Complément d'adresse">
        </div>
        <div class="col-md-4">
            <label class="control-label">Case Postale</label>
            <input type="text" name="adresse[cp]" class="form-control" value="{{ old('adresse.cp', Session::get('adresse.cp')) }}" placeholder="Case Postale">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label class="control-label"><span class="text-danger">NPA</span></label>
            <input type="text" name="adresse[npa]" data-parsley-required class="form-control form-required" value="{{ old('adresse.npa', Session::get('adresse.npa')) }}" placeholder="Code postal">
        </div>
        <div class="col-md-8">
            <label class="control-label"><span class="text-danger">Ville</span></label>
            <input type="text" name="adresse[ville]" data-parsley-required class="form-control form-required" value="{{ old('adresse.ville', Session::get('adresse.ville')) }}" placeholder="Localité">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="control-label">Canton</label>
            {!! Form::select('adresse[canton_id]', [0 => 'Choix'] + $cantons->pluck('title','id')->all() , old('adresse.canton_id', Session::get('adresse.canton_id')), ['class' => 'form-control']) !!}
        </div>
        <div class="col-md-6">
            <label class="control-label">Pays</label>
            {!! Form::select('adresse[pays_id]', $pays->pluck('title','id')->all() , 208, ['class' => 'form-control']) !!}
        </div>
    </div>

</div>