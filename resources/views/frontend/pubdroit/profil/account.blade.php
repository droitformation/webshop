@extends('frontend.pubdroit.profil.index')
    @section('profil')
    @parent

    <!-- start wrapper -->
    <div class="profil-wrapper">

        <h4>Compte</h4>
        <div class="profil-info">
            <form method="post" action="{{ url('pubdroit/profil/account') }}" class="form">{!! csrf_field() !!}
                <fieldset>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Prénom</label>
                        <div class="col-sm-7">
                            <input type="text" name="first_name" value="{{ $user->first_name }}" placeholder="Prénom" class="form-control form-required">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Nom</label>
                        <div class="col-sm-7">
                            <input type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Nom" class="form-control form-required">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-7">
                            <input type="email" name="email" value="{{ $user->email }}" placeholder="E-mail" class="form-control form-required">
                        </div>
                    </div>
                    <hr/>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <a target="_blank" id="resetPassword" href="{{ url('password/reset') }}" class="text-muted">Changer le mot de passe</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="submit" id="saveAccount" class="more-btn">Sauvegarder</button>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="id" value="{{ $user->id }}">
            </form>
        </div>
    </div>
    <!-- end wrapper -->

    @if(!$user->adresses->isEmpty())
        @foreach($user->adresses as $adresse)

                <!-- start wrapper -->
        <div class="profil-wrapper">
            <h4>Adresse</h4>
            <div class="profil-info">
                <form action="{{ url('pubdroit/profil/update') }}" method="POST" class="form">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}
                    <div class="row form-group">
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
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Type d'adresse</label>
                        <div class="col-sm-7">
                            <select class="form-control" name="type">
                                <option {{ $adresse->type == 1 ? 'checked' : '' }} value="1">Contact</option>
                                <option {{ $adresse->type == 2 ? 'checked' : '' }} value="2">Privé</option>
                                <option {{ $adresse->type == 3 ? 'checked' : '' }} value="3">Professionnelle</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Titre</label>
                        <div class="col-sm-7">
                            <select name="civilite_id" class="form-control">
                                <option {{ $adresse && $adresse->civilite_id == 4 ? 'selected' : '' }} value="4"></option>
                                <option {{ $adresse && $adresse->civilite_id == 1 ? 'selected' : '' }} value="1">Monsieur</option>
                                <option {{ $adresse && $adresse->civilite_id == 2 ? 'selected' : '' }} value="2">Madame</option>
                                <option {{ $adresse && $adresse->civilite_id == 3 ? 'selected' : '' }} value="3">Me</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Prénom</label>
                        <div class="col-sm-7">
                            <input type="text" name="first_name" required class="form-control form-required" value="{{ $adresse->first_name }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Nom</label>
                        <div class="col-sm-7">
                            <input type="text" name="last_name" required class="form-control form-required" value="{{ $adresse->last_name }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-7">
                            <input type="text" name="email" class="form-control" value="{{ $adresse->email }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Profession</label>
                        <div class="col-sm-7">
                            {!! Form::select('profession_id', $professions->pluck('title','id')->toArray() , $adresse->profession_id, ['class' => 'form-control', 'placeholder' => 'Choix']) !!}
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Entreprise</label>
                        <div class="col-sm-7">
                            <input type="text" name="company" class="form-control form-required" value="{{ $adresse->company }}">
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Adresse</label>
                        <div class="col-sm-7">
                            <input type="text" name="adresse" required class="form-control form-required" value="{{ $adresse->adresse }}">
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Complément d'adresse</label>
                        <div class="col-sm-7">
                            <input type="text" name="complement" class="form-control" value="{{ $adresse->complement }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Case Postale</label>
                        <div class="col-sm-3 col-xs-6">
                            <input type="text" name="cp" class="form-control" value="{{ $adresse->cp }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">NPA</label>
                        <div class="col-sm-3 col-xs-6">
                            <input type="text" name="npa" required class="form-control form-required" value="{{ $adresse->npa }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Localité</label>
                        <div class="col-sm-7">
                            <input type="text" name="ville" required class="form-control form-required" value="{{ $adresse->ville }}">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Canton</label>
                        <div class="col-sm-7">
                            {!! Form::select('canton_id', $cantons->pluck('title','id')->all() , $adresse->canton_id, ['data-parsley-required' => 'true' ,'class' => 'form-control', 'placeholder' => 'Choix']) !!}
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-4 control-label">Pays</label>
                        <div class="col-sm-7">
                            {!! Form::select('pays_id', $pays->pluck('title','id')->all() , $adresse->pays_id, [ 'data-parsley-required' => 'true' ,'class' => 'form-control', 'placeholder' => 'Choix']) !!}
                        </div>
                    </div>

                    <input type="hidden" name="id" value="{{ $adresse->id }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <hr/>
                    <div class="row form-group">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="more-btn">Sauvegarder</button>
                        </div>
                    </div>
                </form>

            </div>
            @endforeach
        @else
            <p><a href="#addAdress" data-toggle="collapse"><i class="fa fa-plus-circle"></i>&nbsp;Ajouter une adresse de livraison</a></p>

            <div class="collapse" id="addAdress">
                <!-- start wrapper -->
                <div class="profil-wrapper">
                    <h4>Nouvelle adresse</h4>
                    <div class="profil-info">
                        @include('frontend.pubdroit.profil.adresse')
                    </div>
                </div>
                <!-- end wrapper -->
            </div>
        @endif

    </div>
    <!-- end wrapper -->
@endsection