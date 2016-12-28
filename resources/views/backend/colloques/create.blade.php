@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/colloque') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <h3>Ajouter un colloque</h3>

            <form action="{{ url('admin/colloque') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                {!! csrf_field() !!}
                <div class="panel panel-midnightblue">
                    <div class="panel-body" ng-app="upload">

                        <fieldset title="Général"  id="appComponent">
                            <legend>Informations de base</legend>

                            <div class="form-group">
                                <label for="file" class="col-sm-3 control-label">Vignette</label>
                                <div class="col-sm-7">
                                    {!! Form::file('file') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="titre" class="col-sm-3 control-label">Titre</label>
                                <div class="col-sm-6">
                                    {!! Form::text('titre', '' , array('class' => 'form-control required' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block">Requis</p></div>
                            </div>

                            <div class="form-group">
                                <label for="soustitre" class="col-sm-3 control-label">Sous-titre</label>
                                <div class="col-sm-6">
                                    {!! Form::text('soustitre', '' , array('class' => 'form-control' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block"></p></div>
                            </div>

                            <div class="form-group">
                                <label for="sujet" class="col-sm-3 control-label">Sujet</label>
                                <div class="col-sm-6">
                                    {!! Form::text('sujet', '' , array('class' => 'form-control required' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block">Requis</p></div>
                            </div>

                            <div class="form-group">
                                <label for="organisateur" class="col-sm-3 control-label">Organisateur</label>
                                <div class="col-sm-6">
                                    {!! Form::text('organisateur', '' , array('class' => 'form-control required' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block">Requis</p></div>
                            </div>

                            <div class="form-group">
                                <label for="organisateur" class="col-sm-3 control-label">Centres</label>
                                <div class="col-sm-6">

                                    @if(!$centres->isEmpty())
                                        @foreach($centres as $centre)
                                            <label class="checkbox-inline centre">
                                                <input type="checkbox" name="centres[]" value="{{ $centre->id }}"> {{ $centre->name }}
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Adresse principale<br/><small>Indiqué sur bon, bv, facture</small>
                                    <p class="help-block">Ne sont listé que les organisateur avec une adresse</p>
                                </label>
                                <div class="col-sm-6">
                                    <organisateur organisateur="{{ $adresses->first()->id }}" :adresses="{{ $adresses }}"></organisateur>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Endroit</label>
                                <div class="col-sm-6">
                                    <endroit endroit="{{ $locations->first()->id }}" :adresses="{{ $locations }}"></endroit>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="col-sm-3 control-label">Remarques</label>
                                <div class="col-sm-6">
                                    <textarea name="remarques" id="remarques" cols="50" rows="4" class="redactorSimple form-control"></textarea>
                                </div>
                            </div>

                        </fieldset>

                        <fieldset title="Dates">
                            <legend>Dates de l'événement et délais</legend>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Date de début</label>
                                <div class="col-sm-3">
                                    <input type="text" name="start_at" class="form-control datePicker required" id="start_at">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Date de fin</label>
                                <div class="col-sm-3">
                                    <input type="text" name="end_at" class="form-control datePicker" id="end_at">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Délai d'inscription</label>
                                <div class="col-sm-3">
                                    <input type="text" name="registration_at" class="form-control datePicker" id="registration_at">
                                </div>
                            </div>
                        </fieldset>

                    </div>
                    <div class="panel-footer">
                        <p class="text-right"><input type="submit" class="finish btn-success btn" value="Envoyer" /></p>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop