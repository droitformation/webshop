@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-10"><!-- col -->
        <p><a class="btn btn-default" href="{{ redirect()->getUrlGenerator()->previous() }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>

        <div class="panel panel-midnightblue">
            <div class="panel-body">

                <form action="{{ url('admin/adresse') }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form form-horizontal">
                    {!! csrf_field() !!}
                    <h3>Créer une adresse</h3>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Type d'adresse</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="type">
                                <option selected value="1">Contact</option>
                                <option value="2">Privé</option>
                                <option value="3">Professionnelle</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Entreprise</label>
                        <div class="col-sm-7">
                            <input type="text" name="company" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-4">
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
                            <input type="text" name="first_name" data-parsley-required class="form-control form-required" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-5">
                            <input type="text" name="last_name" data-parsley-required class="form-control form-required" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-5">
                            <input type="text" name="email" class="form-control" value="">
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
                        <div class="col-sm-4">
                            {!! Form::select('canton_id', $cantons->lists('title','id')->all() , null, ['data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Canton']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Pays</label>
                        <div class="col-sm-4">
                            {!! Form::select('pays_id', $pays->lists('title','id')->all() , 208, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Pays']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Profession</label>
                        <div class="col-sm-4">
                            {!! Form::select('profession_id', $professions->lists('title','id')->all() ,null, ['class' => 'form-control', 'placeholder' => 'Choix']) !!}
                        </div>
                    </div>
                    <br/><p class="pull-right"><button class="btn btn-primary" type="submit">Enregistrer</button></p>
                </form>

            </div>
        </div>

    </div>
</div>
@stop