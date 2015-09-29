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

                        <form method="POST" enctype="multipart/form-data" class="form-horizontal"
                              flow-init flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
                              flow-files-submitted="$flow.upload()">
                            <div class="uploadBtn">
                                <span class="btn btn-xs btn-info" ng-hide="$flow.files.length"    flow-btn flow-attrs="{accept:'image/*'}">Selectionner image</span>
                                <span class="btn btn-xs btn-warning" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Changer</span>
                                <span class="btn btn-xs btn-danger" ng-show="$flow.files.length"  ng-click="$flow.cancel()">Supprimer</span>
                            </div>
                            <div class="thumbnail big" ng-hide="$flow.files.length"><img src="http://www.placehold.it/560x160/EFEFEF/AAAAAA&text=choisir+une+image" /></div>
                            <div class="thumbnail big" ng-show="$flow.files.length"><img flow-img="$flow.files[0]" /></div>
                            <input type="hidden" class="uploadImage" name="image" value="{[{ $flow.files[0].name }]}">
                        </form>
                    </div>

                    <form action="{{ url('admin/colloque') }}" method="POST" class="form-horizontal" id="wizard">
                        {!! csrf_field() !!}

                        <fieldset title="Step 1">
                            <legend>Général</legend>

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
                                    <select class="form-control required" name="endroit" id="endroitSelect">
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
                                    <textarea name="description" id="description" cols="50" rows="4" class="redactorSimple form-control"></textarea>
                                </div>
                            </div>

                        </fieldset>

                        <fieldset title="Step 2">
                            <legend>Dates</legend>

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

                        <fieldset title="Step 3">
                            <legend>Documents</legend>
                            <div class="form-group">
                                <label for="" class="col-md-3 control-label">Terms and Conditions</label>
                                <div class="col-md-9">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, nemo, atque consequuntur officiis
                                        asperiores consectetur porro labore commodi esse error quisquam nihil illum sunt facere inventore possimus
                                        autem ab voluptates quibusdam non voluptatum suscipit architecto. Illo, facilis, corporis, veritatis dolores
                                        minus quasi iure cupiditate quis autem ducimus nisi obcaecati tenetur sed ea excepturi pariatur consequatur
                                        enim labore officia mollitia. Rerum, voluptatem numquam molestiae recusandae iusto ipsum inventore unde a
                                        ccusantium labore delectus? Doloremque, fugit, sunt libero laboriosam cupiditate sed sequi nostrum saepe.
                                        Mollitia, alias, expedita accusantium porro error autem dolore veniam commodi nesciunt provident vitae
                                        neque. Nostrum, sed, molestias itaque provident inventore natus animi quasi laborum laboriosam facere
                                        ratione aperiam iusto. Non ducimus facere sunt doloribus? Asperiores, natus distinctio quis iure!</p>
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