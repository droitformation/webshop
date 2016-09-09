<form action="{{ url('admin/adresse/'.$adresse->id) }}" data-validate="parsley" method="POST" class="validate-form form-horizontal">
    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
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
        <label class="col-sm-4 control-label">Profession</label>
        <div class="col-sm-7">
            @if(isset($professions) && !$professions->isEmpty())
                <select class="form-control form-required" data-parsley-required name="profession_id">
                    <option value="">Choix</option>
                    @foreach($professions->pluck('title','id')->all() as $id => $pr)
                        <option {{ $id == $adresse->profession_id ? 'selected' : '' }} value="{{ $id }}">{{ $pr }}</option>
                    @endforeach
                </select>
            @endif
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
            @if(isset($cantons) && !$cantons->isEmpty())
                <select class="form-control form-required" data-parsley-required name="canton_id">
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
                <select class="form-control form-required" data-parsley-required name="pays_id">
                    <option value="">Choix</option>
                    @foreach($pays->pluck('title','id')->all() as $id => $p)
                        <option {{ $id == $adresse->pays_id ? 'selected' : '' }} value="{{ $id }}">{{ $p }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <input value="{{ $adresse->id }}" name="id" type="hidden">
    <input value="{{ $adresse->user_id }}" name="user_id" type="hidden">
    <br/>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ url('admin/adresse/'.$adresse->id) }}" method="POST" class="pull-left">
                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                <input type="hidden" value="{{ url('admin/user/'.$adresse->user_id) }}" name="url">
                <button data-what="Supprimer" data-action="{{ $adresse->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
        </div>
    </div>

</form>