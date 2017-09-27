<form action="{{ url('pubdroit/profil/update') }}" method="POST" class="form">
    {!! csrf_field() !!}

    <div class="row form-group">
        <label class="col-sm-4 control-label">Titre</label>
        <div class="col-sm-7">
            <select name="civilite_id" class="form-control">
                <option value="4" selected></option>
                <option value="1">Monsieur</option>
                <option value="2">Madame</option>
            </select>
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Prénom</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control form-required"name="first_name" value="{{ $user->first_name }}">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Nom</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control form-required" name="last_name" value="{{ $user->last_name }}">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Profession</label>
        <div class="col-sm-7">
            {!! Form::select('profession_id', $professions->pluck('title','id')->toArray() , null, ['class' => 'form-control', 'placeholder' => 'Choix']) !!}
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Entreprise</label>
        <div class="col-sm-7">
            <input type="text" name="company" class="form-control form-required" value="">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Adresse</label>
        <div class="col-sm-7">
            <input type="text" name="adresse" required class="form-control form-required" value="">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Complément d'adresse</label>
        <div class="col-sm-7">
            <input type="text" name="complement" class="form-control" value="">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Case Postale</label>
        <div class="col-sm-3 col-xs-6">
            <input type="text" name="cp" class="form-control" value="">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">NPA</label>
        <div class="col-sm-3 col-xs-6">
            <input type="text" name="npa" required class="form-control form-required" value="">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Localité</label>
        <div class="col-sm-7">
            <input type="text" name="ville" required class="form-control form-required" value="">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Canton</label>
        <div class="col-sm-7">
            {!! Form::select('canton_id', $cantons->pluck('title','id')->all() , null, ['data-parsley-required' => 'true' ,'class' => 'form-control', 'placeholder' => 'Choix']) !!}
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-4 control-label">Pays</label>
        <div class="col-sm-7">
            {!! Form::select('pays_id', $pays->pluck('title','id')->all() , null, [ 'data-parsley-required' => 'true' ,'class' => 'form-control', 'placeholder' => 'Choix']) !!}
        </div>
    </div>

    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="email" value="{{ $user->email }}">
    <hr/>
    <div class="row form-group">
        <div class="col-sm-12 text-right">
            <button type="submit" class="more-btn">Sauvegarder</button>
        </div>
    </div>

</form>