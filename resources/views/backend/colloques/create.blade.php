@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/colloque') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter un colloque</h4>
                </div>
                <div class="panel-body" ng-app="upload">

                    <form action="{{ url('admin/colloque') }}" enctype="multipart/form-data" method="POST" class="form-horizontal"
                          flow-init="{query: {'path' : 'files/colloques', 'colloque_id' : 1, 'type' : 'illustration' }}"
                          flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
                          flow-files-submitted="$flow.upload()">
                        {!! csrf_field() !!}

                        <fieldset title="Général">
                            <legend>Informations de base</legend>

                            <div class="form-group">
                                <label for="titre" class="col-sm-3 control-label">Vignette</label>
                                <div class="col-sm-3">
                                    <div class="uploadBtn">
                                        <span class="btn btn-xs btn-info" ng-hide="$flow.files.length"    flow-btn flow-attrs="{accept:'image/*'}">Selectionner image</span>
                                        <span class="btn btn-xs btn-warning" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Changer</span>
                                        <span class="btn btn-xs btn-danger" ng-show="$flow.files.length"  ng-click="$flow.cancel()">Supprimer</span>
                                    </div>
                                    <div class="thumbnail big" ng-hide="$flow.files.length"><img src="http://www.placehold.it/160x180/EFEFEF/AAAAAA&text=vignette" /></div>
                                    <div class="thumbnail big" ng-show="$flow.files.length"><img style="height: 180px;" flow-img="$flow.files[0]" /></div>
                                    <input type="hidden" name="illustration" value="{[{ $flow.files[0].name }]}">
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

                                    @if(!$organisateurs->isEmpty())
                                        @foreach($organisateurs as $organisateur)
                                            <label class="checkbox-inline centre">
                                                <input type="checkbox" name="centres[]" value="{{ $organisateur->id }}"> {{ $organisateur->name }}
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Endroit</label>
                                <div class="col-sm-6 col-xs-8">
                                    <select class="form-control required" name="location_id" id="endroitSelect">
                                        @if(!$locations->isEmpty())
                                            <option value="">Choix</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <br/>
                                    <div id="showEndroit"></div>
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

                        <input type="submit" class="finish btn-success btn" value="Submit" />
                    </form>
                </div>

            </div>

        </div>
    </div>

@stop