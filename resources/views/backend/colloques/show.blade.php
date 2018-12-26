@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <?php $back = session()->has('colloque_archive') ? url('admin/colloque/archive/'.session()->get('colloque_archive')) : url('admin/colloque'); ?>
            <p><a href="{{ $back }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
        <div class="col-md-6 text-right">
            <?php $path =  $colloque->attestation ? url('admin/attestation/'.$colloque->attestation->id) : url('admin/attestation/colloque/'.$colloque->id); ?>
            <p><a href="{{ $path }}" class="btn btn-magenta"><i class="fa fa-calendar-check-o"></i> &nbsp;Attestation</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div id="colloque_edit" class="panel panel-midnightblue">

                <div class="panel-body">

                    <form action="{{ url('admin/colloque/'.$colloque->id) }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                        <input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        <fieldset title="Général" id="appComponent">
                            <legend>Général</legend>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><strong>Visible</strong></label>
                                <div class="col-sm-8">
                                    <label class="radio-inline"><input type="radio" {{ $colloque->visible ? 'checked' : '' }} name="visible" value="1"> Oui</label>
                                    <label class="radio-inline"><input type="radio" {{ !$colloque->visible ? 'checked' : '' }} name="visible" value="0"> Non</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Adresse principale<br/><small>Indiqué sur bon, bv, facture</small><p class="help-block">Ne sont listé que les organisateur avec une adresse</p>
                                </label>
                                <div class="col-sm-8">
                                    <organisateur organisateur="{{ $colloque->adresse->id }}" :adresses="{{ $adresses }}"></organisateur>
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
                                <label for="organisateur" class="col-sm-3 control-label">Capacité de la salle</label>
                                <div class="col-sm-2">
                                    <input name="capacite" type="text" value="{{ $colloque->capacite ?? old('capacite') }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <p class="help-block">Permet de fermer les inscription sur le site</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="organisateur" class="col-sm-3 control-label">Centres</label>
                                <div class="col-sm-8">
                                    @if(!$centres->isEmpty())
                                        @foreach($centres as $centre)
                                            <label class="checkbox-inline centre">
                                                <input type="checkbox" name="centres[]" {{ ($colloque->centres->contains($centre->id) ? 'checked' : '') }} value="{{ $centre->id }}"> {{ $centre->name }}
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Endroit</label>
                                <div class="col-sm-8">
                                    <endroit endroit="{{ $colloque->location_id }}" :adresses="{{ $locations }}"></endroit>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="col-sm-3 control-label">Remarques</label>
                                <div class="col-sm-8">
                                    <textarea name="remarques" id="remarques" cols="50" rows="4" class="redactorSimple form-control">{{ $colloque->remarques ?? old('remarques') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">

                                <label for="description" class="col-sm-3 control-label">Thèmes principaux</label>
                                <div class="col-sm-8">
                                    <textarea name="themes" cols="50" rows="8" class="form-control redactorLimit">{{ $colloque->themes ?? old('themes') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="col-sm-3 control-label">Thèmes principaux</label>
                                <div class="col-sm-8">
                                    <textarea name="themes" cols="50" rows="8" class="form-control redactorLimit">{{ $colloque->themes or old('themes') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row well">
                                    <label class="col-sm-3 control-label"><i class="fa fa-external-link-square"></i> &nbsp;Inscription externes</label>
                                    <div class="col-sm-8 col-xs-6">
                                        <input type="text" class="form-control" name="url" style="margin-top: 10px;" value="{{ $colloque->url }}" placeholder="http://">
                                    </div>
                                </div>
                                <div class="row well">
                                    <label class="col-sm-3 control-label"><i class="fa fa-envelope"></i> &nbsp;Envoyer les email de confirmation<br/> à une autre adresse email</label>
                                    <div class="col-sm-8 col-xs-6">
                                        <input type="text" class="form-control" name="email" style="margin-top: 10px;" value="{{ $colloque->email ?? old('email')  }}" placeholder="Par défaut: {!! Registry::get('inscription.infos.email') !!}">
                                    </div>
                                </div>

                                <div class="row well">
                                    <label class="col-sm-3 control-label">
                                        <strong><i class="fa fa-star"></i> &nbsp; Inscriptions</strong><br/>
                                        Changer le texte envoyé via email<br/>Voir dans config pour le message par défaut
                                    </label>
                                    <div class="col-sm-8 col-xs-6">
                                        <textarea name="notice" cols="50" rows="4" class="redactorSimple form-control">{{ $colloque->notice ?? old('notice') }}</textarea>
                                    </div>
                                </div>

                                <div class="row well" style="background-color: #fff0c9;">
                                    <label class="col-sm-3 control-label">
                                        <strong><i class="fa fa-file-o"></i> &nbsp; Slides</strong><br/>
                                        Changer le texte envoyé via email<br/>Voir dans config pour le message par défaut
                                    </label>
                                    <div class="col-sm-8 col-xs-6">
                                        <textarea name="slide_text" cols="50" rows="4" class="redactorSimple form-control">{{ $colloque->slide_text ?? old('slide_text') }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <legend>Prix</legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Prix</label>
                                <div class="col-sm-8">
                                    <price :prices="{{ $colloque->price_display }}" :colloque="{{ $colloque->id }}"></price>
                                </div>
                            </div>

                            <legend>Conférences</legend>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Multiples conférences</label>
                                <div class="col-sm-8">
                                    <occurrence :prices="{{ $colloque->prices }}" :colloque="{{ $colloque->id }}" :locations="{{ $locations }}" :occurrences="{{ $colloque->occurrence_display }}"></occurrence>
                                </div>
                            </div>

                            <legend>Options</legend>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Options</label>
                                <div class="col-sm-8">
                                    <option-groupe :options="{{ $colloque->option_display }}" :colloque="{{ $colloque->id }}"></option-groupe>
                                </div>
                            </div>

                        </fieldset>

                        <fieldset title="Dates">
                            <legend>Dates</legend>
                            @include('backend.colloques.partials.dates')
                        </fieldset>

                        <fieldset title="Annexes">
                            <legend>Annexes</legend>
                            @include('backend.colloques.partials.annexes')
                        </fieldset>

                        <input type="hidden" name="id" value="{{ $colloque->id }}"><br/>
                        <input type="submit" class="finish btn-primary btn pull-right" value="Envoyer" />
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-4">

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h4><i class="fa fa-file"></i> &nbsp;Documents</h4>
                    <h5><strong>Vignette</strong></h5>
                    @if($colloque->illustration)
                        <div class="thumbnail big">
                            <form action="{{ url('admin/document/'.$colloque->illustration->id) }}" method="POST" class="pull-right">
                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                <button data-action="Vignette" class="btn btn-danger btn-xs deleteAction">x</button>
                            </form>
                            <img style="height: 140px;" src="{{ secure_asset('files/colloques/illustration/'.$colloque->illustration->path) }}" />
                        </div>
                    @else
                        @include('backend.colloques.partials.upload', ['type' => 'illustration', 'name' => 'Illustration'])
                    @endif

                    <h5><strong>Programme</strong></h5>
                    @if($colloque->programme)
                        <div class="colloque-doc-item">
                            <a class="btn btn-default btn-xs" target="_blank" href="files/colloques/programme/{{ $colloque->programme->path }}"><i class="fa fa-file"></i> &nbsp;Le programme</a>
                            <form action="{{ url('admin/document/'.$colloque->programme->id) }}" method="POST" class="pull-right">
                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                <button data-action="Programme" data-what="supprimer" class="btn btn-danger btn-xs deleteAction">x</button>
                            </form>
                        </div>
                    @else
                        @include('backend.colloques.partials.upload', ['type' => 'programme', 'name' => 'Programme'])
                    @endif

                    <?php $documents = $colloque->documents->filter(function ($document, $key) {
                        return $document->type == 'document';
                    }); ?>

                    @if(!$documents->isEmpty())
                        <h5><strong>Documents</strong></h5>
                        @foreach($documents as $document)
                            <div class="colloque-doc-item">
                                <a class="btn btn-default" target="_blank" href="{{ $document->colloque_path }}"><i class="fa fa-file-archive-o"></i> &nbsp;{{ $document->titre }}</a>
                                <form action="{{ url('admin/document/'.$document->id) }}" method="POST" class="pull-right">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <button data-action="{{ $document->titre }}" data-what="supprimer" class="btn btn-danger btn-sm deleteAction">x</button>
                                </form>
                            </div>
                        @endforeach
                        <br/> <br/>
                    @endif

                    <h5><strong>Ajouter un document</strong></h5>
                    <div class="well">
                        <form action="{{ url('admin/uploadFile') }}" method="post" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
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
            </div>
            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h4><i class="fa fa-file-text-o"></i> &nbsp;
                        Slides
                        <div class="btn-group pull-right">
                            <a target="_blank" class="btn btn-default btn-sm" href="{{ url('preview/slides/'.$colloque->id) }}">Voir email</a>
                            <a class="btn btn-warning btn-sm" id="copyBtn" href="{{ url('pubdroit/documents/'.$colloque->id) }}">Copier lien</a>
                            <a class="btn btn-inverse btn-sm" href="{{ url('admin/slide/confirm/'.$colloque->id) }}">Confirmer et envoyer</a>
                        </div>
                    </h4>

                    {!! $colloque->liste && $colloque->liste->send_at ? '<small class="text-muted">Dernier envoi le '.$colloque->liste->send_at.'</small>' : '' !!}

                    @include('backend.colloques.partials.slides', ['colloque' => $colloque])

                    @if(!$colloque->getMedia('slides')->isEmpty())
                        <h5><b>Slides</b></h5>
                        <div class="sortslides" data-id="{{ $colloque->id }}">
                            @foreach($colloque->getMedia('slides') as $slide)
                                @include('backend.colloques.partials.slides', ['colloque' => $colloque, 'slide' => $slide])
                            @endforeach
                        </div>
                    @endif

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
                    @if($colloque->bon)
                        <a target="_blank" class="btn btn-sm btn-default" href="{{ url('admin/document/'.$colloque->id.'/bon') }}">Tester le bon</a>
                    @endif
                    @if($colloque->facture)
                        <a target="_blank" class="btn btn-sm btn-default" href="{{ url('admin/document/'.$colloque->id.'/facture') }}">Tester la facture</a>
                        <a target="_blank" class="btn btn-sm btn-default" href="{{ url('admin/document/'.$colloque->id.'/bv') }}">Tester le bv</a>
                    @endif

                    <a target="_blank" class="btn btn-sm btn-default" href="{{ url('preview/inscriptioncolloque/'.$colloque->id) }}">Voir l'email</a>

                </div>
            </div>

        </div>
    </div>

@stop