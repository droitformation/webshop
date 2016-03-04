@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <p><a href="{{ url('admin/colloque') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
        <div class="col-md-6 text-right">
            <?php $path =  $colloque->attestation ? url('admin/attestation/'.$colloque->attestation->id) : url('admin/attestation/colloque/'.$colloque->id); ?>
            <p><a href="{{ $path }}" class="btn btn-magenta"><i class="fa fa-calendar-check-o"></i> &nbsp;Attestation</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-midnightblue">

                <?php $centers = $colloque->centres->lists('id')->all(); ?>
                    <div class="panel-body">

                        <form action="{{ url('admin/colloque/'.$colloque->id) }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                            <input type="hidden" name="_method" value="PUT">
                            {!! csrf_field() !!}

                            <fieldset title="Général">
                                <legend>Général</legend>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><strong>Visible</strong></label>
                                    <div class="col-sm-8">
                                        <label class="radio-inline"><input type="radio" {{ $colloque->visible ? 'checked' : '' }} name="visible" value="1"> Oui</label>
                                        <label class="radio-inline"><input type="radio" {{ !$colloque->visible ? 'checked' : '' }} name="visible" value="0"> Non</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="titre" class="col-sm-3 control-label">
                                        Adresse principale<br/><small>Indiqué sur bon, bv, facture</small>
                                        <p class="help-block">Ne sont listé que les organisateur avec une adresse</p>
                                    </label>
                                    <div class="col-sm-8">
                                        <div id="choixAdresse">
                                            <select class="form-control form-required required" autocomplete="off" name="adresse_id" id="adresseSelect">
                                                <?php
                                                    $adresses = $organisateurs->reject(function ($item) {
                                                        return $item->adresse == '';
                                                    });
                                                ?>
                                                @if(!$adresses->isEmpty())
                                                    <option value="">Choix</option>
                                                    @foreach($adresses as $adresse)
                                                        <option {{ ($colloque->adresse->id == $adresse->id ? 'selected' : '') }} value="{{ $adresse->id }}">{{ $adresse->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div><br/>
                                        <div class="thumbnail thumbnail-colloque">
                                            <div id="showAdresse">{!! $colloque->adresse->adresse !!}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="titre" class="col-sm-3 control-label">Titre</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('titre', $colloque->titre , array('class' => 'form-control form-required required' )) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="soustitre" class="col-sm-3 control-label">Sous-titre</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('soustitre', $colloque->soustitre , array('class' => 'form-control' )) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sujet" class="col-sm-3 control-label">Sujet</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('sujet', $colloque->sujet , array('class' => 'form-control form-required required' )) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="organisateur" class="col-sm-3 control-label">Organisateur</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('organisateur', $colloque->organisateur , array('class' => 'form-control form-required required' )) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="organisateur" class="col-sm-3 control-label">Centres</label>
                                    <div class="col-sm-8">
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
                                    <div class="col-sm-8">
                                        <select class="form-control form-required required" name="location_id" id="endroitSelect">
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
                                </div>

                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Remarques</label>
                                    <div class="col-sm-8">
                                        <textarea name="remarques" id="remarques" cols="50" rows="4" class="redactorSimple form-control">{{ $colloque->remarques }}</textarea>
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset title="Dates">

                                <legend>Dates</legend>
                                @include('backend.colloques.partials.dates')

                            </fieldset>
                            <fieldset title="Prix">

                                <legend>Prix</legend>
                                @include('backend.colloques.partials.prices',['type' => 'public', 'title' => 'Prix public'])
                                @include('backend.colloques.partials.prices',['type' => 'admin' , 'title' => 'Prix admin'])

                            </fieldset>
                            <fieldset title="Options">

                                <legend>Options</legend>
                                @include('backend.colloques.partials.options')

                            </fieldset>
                            <fieldset title="Annexes">

                                <legend>Annexes</legend>
                                @include('backend.colloques.partials.annexes')

                            </fieldset>

                            <input type="hidden" name="id" value="{{ $colloque->id }}"><br/>
                            <input type="submit" class="finish btn-success btn pull-right" value="Envoyer" />
                        </form>
                    </div>
            </div>

        </div>
        <div class="col-md-4">

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h4><i class="fa fa-file"></i> &nbsp;Documents</h4>
                    <h5>Vignette</h5>
                    @if($colloque->illustration)
                        <div class="thumbnail big">
                            <form action="{{ url('admin/document/'.$colloque->illustration->id) }}" method="POST" class="pull-right">
                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                <button data-action="Vignette" class="btn btn-danger btn-sm deleteAction">x</button>
                            </form>
                            <img style="height: 140px;" src="{{ asset('files/colloques/illustration/'.$colloque->illustration->path) }}" />
                        </div>
                    @else
                        @include('backend.colloques.partials.upload', ['type' => 'illustration', 'name' => 'Illustration'])
                    @endif

                    <h5>Programme</h5>
                    @if($colloque->programme)
                        <div class="colloque-doc-item">
                            <a class="btn btn-default" target="_blank" href="files/colloques/programme/{{ $colloque->programme->path }}"><i class="fa fa-file"></i> &nbsp;Le programme</a>
                            <form action="{{ url('admin/document/'.$colloque->programme->id) }}" method="POST" class="pull-right">
                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                <button data-action="Programme" data-what="supprimer" class="btn btn-danger btn-sm deleteAction">x</button>
                            </form>
                        </div>
                    @else
                        @include('backend.colloques.partials.upload', ['type' => 'programme', 'name' => 'Programme'])
                    @endif

                    <h5>Documents</h5>

                        @if($colloque->documents)
                            @foreach($colloque->documents as $document)
                                @if($document->type == 'document')
                                    <div class="colloque-doc-item">
                                        <a class="btn btn-default" target="_blank" href="{{ $document->colloque_path }}"><i class="fa fa-file-archive-o"></i> &nbsp;{{ $document->titre }}</a>
                                        <form action="{{ url('admin/document/'.$document->id) }}" method="POST" class="pull-right">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-action="{{ $document->titre }}" data-what="supprimer" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        <br/> <br/>
                        <h5>Ajouter un document</h5>
                        <form action="{{ url('admin/uploadFile') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <p><input type="file" required name="file"></p>

                                    <div class="input-group">
                                        <input type="text" class="form-control" required name="titre" value="" placeholder="Titre du fichier">
                                        <span class="input-group-btn">
                                           <button type="submit" class="btn btn-info">Ajouter</button>
                                        </span>
                                    </div><!-- /input-group -->

                                    <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                                    <input type="hidden" name="path" value="files/colloques">
                                    <input type="hidden" name="type" value="document">
                                </div>
                            </div>
                        </form>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h4><i class="fa fa-file-archive-o"></i> &nbsp;Spécialisation</h4>
                    <ul id="specialisations" data-model="colloque" data-id="{{ $colloque->id }}">
                        @if(!$colloque->specialisations->isEmpty())
                            @foreach($colloque->specialisations as $specialisation)
                                <li>{{ $specialisation->title }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h4><i class="fa fa-file"></i> &nbsp;Tester les documents</h4>
                    <a target="_blank" class="btn btn-sm btn-default" href="{{ url('admin/colloque/generate/'.$colloque->id.'/bon') }}">Tester le bon</a>
                    <a target="_blank" class="btn btn-sm btn-default" href="{{ url('admin/colloque/generate/'.$colloque->id.'/facture') }}">Tester la facture</a>
                    <a target="_blank" class="btn btn-sm btn-default" href="{{ url('admin/colloque/generate/'.$colloque->id.'/bv') }}">Tester le bv</a>
                </div>
            </div>

        </div>
    </div>

@stop