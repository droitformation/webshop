@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/colloque') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Éditer un colloque</h4>
                </div>

                <?php $centers = $colloque->centres->lists('id')->all();  ?>
                    <div class="panel-body" ng-app="upload">

                        <form action="{{ url('admin/colloque/'.$colloque->id) }}" enctype="multipart/form-data" method="POST" class="form-horizontal"
                              flow-init="{query: {'path' : 'files/colloques', 'colloque_id' : <?php echo $colloque->id; ?>, 'type' : 'illustration' , '_token': <?php echo csrf_token(); ?> }}"
                              flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
                              flow-files-submitted="$flow.upload()">
                            <input type="hidden" name="_method" value="PUT">
                            {!! csrf_field() !!}

                            <fieldset title="Général">
                                <legend>Général</legend>

                                <div class="form-group">
                                    <label for="titre" class="col-sm-3 control-label">Vignette</label>
                                    <div class="col-sm-3">
                                        <div class="uploadBtn">
                                            <span class="btn btn-xs btn-info"    ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Selectionner image</span>
                                            <span class="btn btn-xs btn-warning" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Changer</span>
                                            <span class="btn btn-xs btn-danger"  ng-show="$flow.files.length" ng-click="$flow.cancel()">Supprimer</span>
                                        </div>

                                        <div class="thumbnail big" ng-hide="$flow.files.length">
                                            <img style="height: 180px;" src="{{ asset($colloque->illustration) }}" />
                                        </div>
                                        <div class="thumbnail big" ng-show="$flow.files.length"><img style="height: 180px;" flow-img="$flow.files[0]" /></div>
                                        <input type="hidden" name="illustration" value="{[{ $flow.files[0].name }]}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="titre" class="col-sm-3 control-label">Titre</label>
                                    <div class="col-sm-6">
                                        {!! Form::text('titre', $colloque->titre , array('class' => 'form-control required' )) !!}
                                    </div>
                                    <div class="col-sm-3"><p class="help-block">Requis</p></div>
                                </div>

                                <div class="form-group">
                                    <label for="soustitre" class="col-sm-3 control-label">Sous-titre</label>
                                    <div class="col-sm-6">
                                        {!! Form::text('soustitre', $colloque->soustitre , array('class' => 'form-control' )) !!}
                                    </div>
                                    <div class="col-sm-3"><p class="help-block"></p></div>
                                </div>

                                <div class="form-group">
                                    <label for="sujet" class="col-sm-3 control-label">Sujet</label>
                                    <div class="col-sm-6">
                                        {!! Form::text('sujet', $colloque->sujet , array('class' => 'form-control required' )) !!}
                                    </div>
                                    <div class="col-sm-3"><p class="help-block">Requis</p></div>
                                </div>

                                <div class="form-group">
                                    <label for="organisateur" class="col-sm-3 control-label">Organisateur</label>
                                    <div class="col-sm-6">
                                        {!! Form::text('organisateur', $colloque->organisateur , array('class' => 'form-control required' )) !!}
                                    </div>
                                    <div class="col-sm-3"><p class="help-block">Requis</p></div>
                                </div>

                                <div class="form-group">
                                    <label for="organisateur" class="col-sm-3 control-label">Centres</label>
                                    <div class="col-sm-6">
                                        @if(!$organisateurs->isEmpty())
                                            @foreach($organisateurs as $organisateur)
                                                <label class="checkbox-inline centre">
                                                    <input type="checkbox" name="centres[]" {{ (in_array($organisateur->id,$centers) ? 'checked' : '') }} value="{{ $organisateur->id }}"> {{ $organisateur->name }}
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Endroit</label>
                                    <div class="col-sm-6 col-xs-6">
                                        <select class="form-control required" name="location_id" id="endroitSelect">
                                            @if(!$locations->isEmpty())
                                                <option value="">Choix</option>
                                                @foreach($locations as $location)
                                                    <option {{ ($colloque->location_id == $location->id ? 'selected' : '') }} value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <br/>
                                        <div id="showEndroit"></div>
                                    </div>
                                    <div class="col-sm-3"><p class="help-block">Requis</p></div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Remarques</label>
                                    <div class="col-sm-6">
                                        <textarea name="remarques" id="remarques" cols="50" rows="4" class="redactorSimple form-control">{{ $colloque->remarques }}</textarea>
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset title="Dates">
                                <legend>Dates</legend>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Date de début</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="start_at" class="form-control datePicker required" value="{{ $colloque->start_at->format('Y-m-d') }}" id="start_at">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Date de fin</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="end_at" class="form-control datePicker" value="{{ ($colloque->end_at ? $colloque->end_at->format('Y-m-d') : '') }}" id="end_at">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Délai d'inscription</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="registration_at" class="form-control datePicker" value="{{ $colloque->registration_at->format('Y-m-d') }}" id="registration_at">
                                    </div>
                                </div>

                            </fieldset>

                            <input type="hidden" name="id" value="{{ $colloque->id }}">
                            <input type="submit" class="finish btn-success btn" value="Submit" />
                        </form>
                    </div>
            </div>

        </div>
        <div class="col-md-4">

            <div class="panel panel-gray">
                <div class="panel-heading">Programme</div>
                <div class="panel-body">

                    @if($colloque->programme)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a class="btn btn-default" target="_blank" href="files/colloques/programme/{{ $colloque->programme->path }}"><i class="fa fa-file"></i> &nbsp;Le programme</a>
                                <form action="{{ url('admin/document/'.$colloque->programme->id) }}" method="POST" class="pull-right">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <button data-action="Programme" class="btn btn-danger btn-sm deleteAction">x</button>
                                </form>
                            </li>
                        </ul>
                    @else
                    <form action="{{ url('admin/uploadFile') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <input type="file" name="file">
                                    <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                                    <input type="hidden" name="path" value="files/colloques">
                                    <input type="hidden" name="type" value="programme">
                                    <input type="hidden" name="name" value="Programme">
                                </div>
                            </div>
                            <div class="col-sm-2 text-right">
                                <button type="submit" class="btn btn-info">Ok</button>
                            </div>
                        </div>
                    </form>
                    @endif

                </div>
            </div>

            <div class="panel panel-gray">
                <div class="panel-heading">Documents</div>
                <div class="panel-body">
                    <ul class="list-group">
                        @if($colloque->documents)
                            @foreach($colloque->documents as $document)
                                @if($document->type == 'document')
                                    <li class="list-group-item">
                                        <a class="btn btn-default" target="_blank" href="{{ $document->path }}"><i class="fa fa-file-archive-o"></i> &nbsp;{{ $document->titre }}</a>
                                        <form action="{{ url('admin/document/'.$document->id) }}" method="POST" class="pull-right">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-action="{{ $document->titre }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                        <li class="list-group-item">
                            <form action="{{ url('admin/uploadFile') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <p><input type="text" class="form-control" required name="titre" value="" placeholder="Nom"></p>
                                        <p><input type="file" required name="file"></p>
                                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                                        <input type="hidden" name="path" value="files/colloques">
                                        <input type="hidden" name="type" value="document">
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <button type="submit" class="btn btn-info">Ok</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

@stop