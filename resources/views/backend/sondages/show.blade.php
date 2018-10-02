@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-4"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/sondage') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->

<div class="row">
    <div class="col-md-9">

        <form action="{{ url('admin/sondage/'.$sondage->id) }}" method="POST" class="form-horizontal">
            <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3>Sondage</h3>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong>Type de sondage</strong></label>
                        <div class="col-sm-8">
                            <label class="radio-inline"><input class="typeSondage" type="radio" {{ $sondage->marketing ? 'checked' : '' }} name="marketing" value="1"> Sondage marketing</label>
                            <label class="radio-inline"><input class="typeSondage" type="radio" {{ !$sondage->marketing ? 'checked' : '' }} name="marketing" value=""> Sondage pour colloque</label>
                        </div>
                    </div>

                    <div class="form-group" id="sondageColloque" style="display:{{ $sondage->marketing ? 'none' : 'block' }};">
                        <label for="message" class="col-sm-3 control-label">Colloque</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" name="colloque_id" class="form-control">
                                <option value="">Choisir le colloque</option>
                                @if(!$colloques->isEmpty())
                                    @foreach($colloques as $colloque)
                                        <option {{ (old('colloque_id') == $colloque->id ) || ($sondage->colloque_id == $colloque->id) ? 'selected' : '' }} value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div id="sondageMarketing" style="display: {{ !$sondage->marketing ? 'none' : 'block' }};">
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-6">
                                <input type="text" name="title" value="{{ $sondage->title }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Description du sondage marketing</label>
                            <div class="col-sm-6">
                                <textarea name="description" class="form-control redactorSimple">{{ $sondage->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Valide jusqu'au</label>
                        <div class="col-sm-4">
                            <input type="text" name="valid_at" required value="{{ $sondage->valid_at->format('Y-m-d') }}" class="form-control datePicker required">
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <input type="hidden" name="id" value="{{ $sondage->id }}" />
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="panel panel-midnightblue">
            <div class="panel-body">
                <h3 style="margin-bottom: 20px;">Questions  <a href="{{ url('admin/avis/create') }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> &nbsp;Ajouter une question au catalogue</a></h3>

                <h4>Ajouter des questions</h4>

                <form action="{{ url('admin/sondageavis') }}" method="POST">{!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ $sondage->id }}" />
                    <div class="input-group">

                        @if(!$avis->isEmpty())
                            <select class="form-control" name="question_id[]" multiple>
                                @foreach($avis as $question)
                                    @if(!$question->hidden)
                                        @if(!$sondage->avis->contains('id',$question->id))
                                            <option value="{{ $question->id }}">{{ strip_tags($question->question) }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                            <span class="input-group-btn" style="vertical-align:top; padding-left: 5px;">
                                <button class="btn btn-primary" type="submit">Ajouter</button>
                            </span>
                        @endif

                    </div><!-- /input-group -->
                </form>

                <hr/>

                <h4>Liste des questions</h4>
                <div class="row">
                    <p class="col-md-6"><span class="avis-hidden-square"></span> Questions cachées</p>
                    <p class="col-md-6 text-right"><small class="text-muted"><i class="fa fa-crosshairs"></i>
                            &nbsp;Cliquez-glissez les pages pour changer l'ordre des questions.</small></p>
                </div>

                @if(!$sondage->avis->isEmpty())
                    <ol class="sortquestion" data-id="{{ $sondage->id }}">
                        @foreach($sondage->avis as $avis)
                            <li class="form-group type-choix question-item sondage-question-item {{ $avis->hidden ? 'avis-hidden' : '' }}" id="question_rang_{{ $avis->id }}">
                                <div class="sondage-question-list">
                                    {!! $avis->question !!}
                                </div>
                                <form action="{{ url('admin/sondageavis/'.$sondage->id) }}" method="POST" class="pull-right">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <input type="hidden" name="question_id" value="{{ $avis->id }}" />
                                    <button class="btn btn-danger btn-xs">Retirer</button>
                                </form>
                                <div class="clearfix"></div>
                            </li>
                        @endforeach
                    </ol>
                @endif

            </div>
        </div>

    </div>
    <div class="col-md-3">


            <?php $url = base64_encode(json_encode(['sondage_id' => $sondage->id, 'email' => config('mail.from.address'),'isTest'  => 1])); ?>

            <a target="_blank" href="{{ url('reponse/create/'.$url) }}" class="btn btn-success btn-block"><i class="fa fa-eye"></i> &nbsp;Prévisualiser le sondage</a>
            <a class="btn btn-info btn-block" href="{{ url('admin/reponse/'.$sondage->id) }}"><i class="fa fa-bullhorn"></i> &nbsp;Voir les réponses</a>

            @if($sondage->colloque_id && isset($sondage->liste))
                <form action="{{ url('admin/sondage/updateList') }}" style="margin-top: 10px;" method="POST">{!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ $sondage->id }}" />
                    <input type="hidden" name="colloque_id" value="{{ $sondage->colloque_id }}" />
                    <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-users"></i> &nbsp;Màj la liste des participants</button>
                    <br/><h4>Liste crée</h4>
                    <div class="well well-sm mt-10">
                        <p><strong>{{ $sondage->liste->title }}</strong></p>
                        <p><i>Nombre de participants:</i> &nbsp;<strong>{{ $sondage->liste->emails->count() }}</strong></p>
                        <p><i>Dernière mise à jour: </i> &nbsp;<strong>{{ $sondage->liste->updated_at->formatLocalized('%d %b %Y') }}</strong></p>
                    </div>
                </form>
            @elseif($sondage->colloque_id)
                <form action="{{ url('admin/sondage/createList') }}" style="margin-top: 10px;" method="POST">{!! csrf_field() !!}
                    <input type="hidden" name="colloque_id" value="{{ $sondage->colloque_id }}" />
                    <button class="btn btn-warning btn-block" type="submit"><i class="fa fa-users"></i> &nbsp;Créer une liste de participants</button>
                </form>
            @endif

    </div>
</div>

@stop