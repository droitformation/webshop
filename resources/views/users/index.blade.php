@extends('layouts.user')
@section('content')

<div class="col-md-9 content">

    <div class="panel panel-default">
        <div class="panel-heading">Vos données</div>
        <div class="panel-body">

            {!! Form::open(array('method' => 'PUT','class'  => 'form-horizontal','url' => array('adresse/'.$user->adresse_livraison->id))) !!}
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Prénom</label>
                        <div class="col-sm-7">
                            <input type="text" name="first_name" value="{{ $user->first_name }}" placeholder="Prénom" class="form-control form-required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nom</label>
                        <div class="col-sm-7">
                            <input type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Nom" class="form-control form-required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-7">
                            <input type="email" name="email" value="{{ $user->email }}" placeholder="E-mail" class="form-control form-required">
                            <p class="help-block">vaut comme adresse de login</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-7">
                            <a href="{{ url('password/new') }}" class="btn btn-xs btn-warning">Changer le mot de passe</a>
                        </div>
                    </div>

                    <br/>
                    <div class="pull-right"><button type="submit" class="btn btn-primary">Sauvegarder</button></div>

                </fieldset>
            {!! Form::hidden('id', $user->id) !!}
            {!! Form::close() !!}

        </div><!-- end panel body -->
    </div><!-- end panel -->

    <div class="panel panel-default">
        <div class="panel-heading">Adresse de livraison</div>
        <div class="panel-body">

            {!! Form::open(array('method' => 'PUT','class'  => 'form-horizontal','url' => array('profil/update'))) !!}

            <fieldset>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Entreprise</label>
                    <div class="col-sm-7">
                        <input type="text" name="company" class="form-control" value="{{ $user->adresse_livraison->company }}" placeholder="Entreprise">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Titre</label>
                    <div class="col-sm-7">
                        <?php $civilite = $user->adresse_livraison->civilite_id; ?>
                        <label class="radio-inline">
                            &nbsp;<input type="radio" data-parsley-required name="civilite_id" {{ $civilite == 1 ? 'checked' : ''}} value="1"> Monsieur&nbsp;
                        </label>
                        <label class="radio-inline">
                            &nbsp;<input type="radio" data-parsley-required name="civilite_id" {{ $civilite == 2 ? 'checked' : ''}} value="2"> Madame&nbsp;
                        </label>
                        <label class="radio-inline">
                            <input type="radio" data-parsley-required name="civilite_id" {{ $civilite == 3 ? 'checked' : ''}} value="3"> Me
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Prénom</label>
                    <div class="col-sm-7">
                        <input type="text" name="first_name" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->first_name }}" placeholder="Prénom">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nom</label>
                    <div class="col-sm-7">
                        <input type="text" name="last_name" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->last_name }}" placeholder="Nom">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Téléphone</label>
                    <div class="col-sm-7">
                        <input type="text" name="telephone" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->telephone }}" placeholder="telephone">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Mobile</label>
                    <div class="col-sm-7">
                        <input type="text" name="mobile" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->mobile }}" placeholder="mobile">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Fax</label>
                    <div class="col-sm-7">
                        <input type="text" name="fax" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->fax }}" placeholder="fax">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Profession</label>
                    <div class="col-sm-7">
                        {!! Form::select('profession_id', $professions->lists('title','id')->all() , $user->adresse_livraison->profession_id, ['class' => 'form-control form-required', 'placeholder' => 'Canton']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Adresse</label>
                    <div class="col-sm-7">
                        <input type="text" name="adresse" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->adresse }}" placeholder="Adresse">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Complément d'adresse</label>
                    <div class="col-sm-7">
                        <input type="text" name="complement" class="form-control" value="{{ $user->adresse_livraison->complement }}" placeholder="Complément d'adresse">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Case Postale</label>
                    <div class="col-sm-3 col-xs-6">
                        <input type="text" name="cp" class="form-control" value="{{ $user->adresse_livraison->cp }}" placeholder="Case Postale">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Code postal</label>
                    <div class="col-sm-3 col-xs-6">
                        <input type="text" name="npa" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->npa }}" placeholder="Code postal">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Localité</label>
                    <div class="col-sm-7">
                        <input type="text" name="ville" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->ville }}" placeholder="Localité">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Canton</label>
                    <div class="col-sm-7">
                        {!! Form::select('canton_id', $cantons->lists('title','id')->all() , $user->adresse_livraison->canton_id, ['data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Canton']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Pays</label>
                    <div class="col-sm-7">
                        {!! Form::select('pays_id', $pays->lists('title','id')->all() , $user->adresse_livraison->pays_id, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Pays']) !!}
                    </div>
                </div>

                <br/>
                <div class="pull-right"><button type="submit" class="btn btn-primary">Sauvegarder</button></div>
                {!! Form::hidden('id', $user->adresse_livraison->id) !!}
                {!! Form::hidden('user_id', $user->id) !!}
                {!! Form::hidden('livraison', 1) !!}
                {!! Form::hidden('type', $user->adresse_livraison->type) !!}

            </fieldset>
            {!! Form::close() !!}

        </div><!-- end panel body -->
    </div><!-- end panel -->

</div>

@endsection
