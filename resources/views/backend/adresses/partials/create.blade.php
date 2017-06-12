<form action="{{ url('admin/adresse') }}" data-validate="parsley" method="POST" class="validate-form form-horizontal">{!! csrf_field() !!}
    <h3>Créer une adresse</h3>

    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-7">
            <p class="text-required-if">* Champ obligatoire sans prénom/nom</p>
            <p class="text-required">* Champs obligatoire pour commandes et inscriptions</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Entreprise</label>
        <div class="col-sm-7">
            <input type="text" name="company" class="form-control form-required-if" value="{{ isset($user) ? $user->company : '' }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Titre</label>
        <div class="col-sm-3 col-xs-12">
            <select class="form-control" name="civilite_id">
                <option selected value="4"></option>
                <option value="1">Monsieur</option>
                <option value="2">Madame</option>
                <option value="3">Me</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Prénom</label>
        <div class="col-sm-5">
            <input type="text" name="first_name" data-parsley-required class="form-control form-required" value="{{ isset($user) ? $user->first_name : '' }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nom</label>
        <div class="col-sm-5">
            <input type="text" name="last_name" data-parsley-required class="form-control form-required" value="{{ isset($user) ? $user->last_name : '' }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Email</label>
        <div class="col-sm-5">
            <input type="text" name="email" class="form-control" value="{{ isset($user) ? $user->email : '' }}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Téléphone</label>
        <div class="col-sm-5">
            <input type="text" name="telephone" class="form-control" value="">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Mobile</label>
        <div class="col-sm-5">
            <input type="text" name="mobile" class="form-control" value="">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Profession</label>
        <div class="col-sm-7 col-xs-12">
            @if(isset($professions) && !$professions->isEmpty())
                <select class="form-control" name="profession_id">
                    <option value="">Choix</option>
                    @foreach($professions->pluck('title','id')->all() as $id => $pr)
                        <option value="{{ $id }}">{{ $pr }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Adresse</label>
        <div class="col-sm-7">
            <input type="text" name="adresse" data-parsley-required class="form-control form-required" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Complément d'adresse</label>
        <div class="col-sm-7">
            <input type="text" name="complement" class="form-control" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">CP</label>
        <div class="col-sm-3 col-xs-6">
            <input type="text" name="cp" class="form-control" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NPA</label>
        <div class="col-sm-3 col-xs-6">
            <input type="text" name="npa" data-parsley-required class="form-control form-required" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Localité</label>
        <div class="col-sm-6">
            <input type="text" name="ville" data-parsley-required class="form-control form-required" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Canton</label>
        <div class="col-sm-3 col-xs-12">
            @if(isset($cantons) && !$cantons->isEmpty())
                <select class="form-control" name="canton_id">
                    <option value="">Choix</option>
                    @foreach($cantons->pluck('title','id')->all() as $id => $c)
                        <option value="{{ $id }}">{{ $c }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Pays</label>
        <div class="col-sm-3 col-xs-12">
            @if(isset($pays) && !$pays->isEmpty())
                <select class="form-control" name="pays_id">
                    <option value="">Choix</option>
                    @foreach($pays->pluck('title','id')->all() as $id => $p)
                        <option {{ $id == 208 ? 'selected' : '' }} value="{{ $id }}">{{ $p }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    @if(isset($user))
        <input type="hidden" name="user_id" value="{{ $user->id }}">
    @endif

    <input type="hidden" name="type" value="1">

    <br/><p class="pull-right"><button class="btn btn-primary" type="submit">Enregistrer</button></p>
</form>