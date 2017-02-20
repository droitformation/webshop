@extends('auth.layouts.master')
@section('content')

	<form class="form-horizontal" id="registeraccount" role="form" method="POST" action="register">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
        {!! Honeypot::generate('my_name', 'my_time') !!}
		<div class="panel-body">
			<h3 style="margin-bottom: 15px;margin-top:0;">Créer un compte</h3>

            <div class="row">
                <div class="col-md-4"> <!-- General -->
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Titre</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select name="civilite_id" class="form-control">
                                    <option {{ old('civilite_id') == 4 ? 'selected' : '' }} value="4">Choix</option>
                                    <option {{ old('civilite_id') == 1 ? 'selected' : '' }} value="1">Monsieur</option>
                                    <option {{ old('civilite_id') == 2 ? 'selected' : '' }} value="2">Madame</option>
                                    <option {{ old('civilite_id') == 3 ? 'selected' : '' }} value="3">Me</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Prénom</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control form-required" autocomplete="off" name="first_name" value="{{ old('first_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Nom</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control form-required" autocomplete="off" name="last_name" value="{{ old('last_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Email</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control form-required" autocomplete="off" id="email" name="email" value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control form-required" autocomplete="off" id="password" name="password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Confirmation du mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control form-required" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div> <!-- END General -->
                <div class="col-md-1"></div>
                <div class="col-md-7"> <!-- Adresse -->
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Entreprise</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                <input type="text" class="form-control" autocomplete="off" name="company" value="{{ old('company') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>NPA</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dot-circle-o"></i></span>
                                <input type="text" class="form-control form-required"  name="npa" value="{{ old('npa') }}">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <label>Adresse</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                <input type="text" class="form-control form-required" name="adresse" value="{{ old('adresse') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Case Postale</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                                <input type="text" class="form-control" name="cp" value="{{ old('cp') }}">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <label>Complément d'adresse</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                                <input type="text" class="form-control" name="complement" value="{{ old('complement') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Ville</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                <input type="text" class="form-control form-required" name="ville" value="{{ old('ville') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Canton</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-puzzle-piece"></i></span>
                                {!! Form::select('canton_id', $cantons->pluck('title','id')->all() , null, ['class' => 'form-control', 'required' => 'required','placeholder' => 'Choix']) !!}
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="livraison" value="1">

                </div><!-- END Adresse -->
            </div><!-- END row -->

		</div><!-- END panel body -->
		<div class="panel-footer">
			<div class="pull-right">
				<button type="submit" class="btn btn-inverse">Envoyer</button>
			</div>
			<div class="clearfix"></div>
		</div>
	</form>

@stop
