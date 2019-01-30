@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options" style="margin-bottom: 10px;">
                <a href="{{ url('build/newsletter') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i>  &nbsp;&nbsp;Retour aux newsletter</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" id="appComponent">

                <form action="{{ url('build/newsletter/'.$newsletter->id) }}" data-validate="parsley" method="POST" enctype="multipart/form-data" class="validate-form form-horizontal">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                    <div class="panel-heading">
                        <h4>&Eacute;diter la newsletter</h4>
                    </div>
                    <div class="panel-body">

                        <newsletter-type :specialisations="{{ $specialisations }}" :tags="{{ json_encode($newsletter->specialisations->pluck('id')->toArray()) }}" static="{{ $newsletter->static }}"></newsletter-type>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5">
                                <input type="text" required class="form-control" name="titre" value="{{ $newsletter->titre }}">
                            </div>
                        </div>

                        @if($newsletter->static)
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Nom de la liste</label>
                                <div class="col-sm-5">
                                    <select class="form-control" required name="list_id">
                                        <option value="">Choix de la liste</option>
                                        @if(!empty($lists))
                                            @foreach($lists as $list)
                                                <option {{ $newsletter->list_id == $list['ID'] ? 'selected' :'' }} value="{{ $list['ID'] }}">{{ $list['Name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(config('newsletter.multi') && isset($sites))
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Site</label>
                                <div class="col-sm-3">
                                    @if(!$sites->isEmpty())
                                        <select class="form-control" name="site_id">
                                            <option value="">Appartient au site</option>
                                            @foreach($sites as $site)
                                                <option {{ $newsletter->site_id == $site->id ? 'selected' : '' }} value="{{ $site->id }}">{{ $site->nom }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Envoyé par</label>
                            <div class="col-sm-5">
                                <input type="text" required class="form-control" name="from_name" value="{{ $newsletter->from_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Email d'envoi</label>
                            <div class="col-sm-5">
                                <input type="text" required class="form-control" name="from_email" value="{{ $newsletter->from_email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Email de retour</label>
                            <div class="col-sm-5">
                                <input type="text" required class="form-control" name="return_email" value="{{ $newsletter->return_email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Lien de désinscription</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">{{ url('/') }}/</span>
                                    <input type="text" required class="form-control" name="unsuscribe" value="{{ $newsletter->unsuscribe }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Couleur principale</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control colorpicker" name="color" value="{{ $newsletter->color }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Couleur secondaire</label>
                            <div class="col-sm-3">
                                <input type="text"  class="form-control colorpicker" name="second_color" value="{{ $newsletter->second_color }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Bannière avec logos</label>
                            <div class="col-sm-6">
                                @if($newsletter->banniere_logos)
                                    <p><img style="border: 1px solid #ddd;" src="{{ secure_asset($newsletter->banniere_logos) }}" alt="Logos" /></p>
                                @endif
                                <input type="file" name="logos">
                                <p class="help-block">Taille max 600x130px</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Bannière de titre</label>
                            <div class="col-sm-6">
                                @if($newsletter->banniere_header)
                                    <p><img style="border: 1px solid #ddd;" src="{{ secure_asset($newsletter->banniere_header) }}" alt="Header" /></p>
                                @endif
                                <input type="file" name="header">
                                <p class="help-block">Taille max 600x160px</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Logo soutien</label>
                            <div class="col-sm-6">
                                <div class="well">
                                    @if($newsletter->logo_soutien)
                                        <p><img style="border: 1px solid #ddd;" src="{{ secure_asset($newsletter->logo_soutien) }}" alt="Soutien" /></p>
                                    @endif
                                    <input type="file" name="soutien">
                                    <p class="help-block">Taille max 105x50px</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9"><h4>Configurations affichage</h4></div>
                        </div>

                        <div class="well">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Lien vers la newsletter en pdf</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline"><input {{ $newsletter->pdf ? 'checked' : '' }} type="radio" name="pdf" value="1"> Oui</label>
                                    <label class="radio-inline"><input {{ !$newsletter->pdf ? 'checked' : '' }} type="radio" name="pdf" value=""> Non</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Titre "Commentaire" ou "Analyse"</label>
                                <div class="col-sm-3">
                                    <input type="text"  class="form-control" name="comment_title" value="{{ $newsletter->comment_title }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Afficher le titre "Commentaire" ou "Analyse"</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline"><input {{ $newsletter->comment ? 'checked' : '' }} type="radio" name="comment" value="1"> Oui</label>
                                    <label class="radio-inline"><input {{ !$newsletter->comment ? 'checked' : '' }} type="radio" name="comment" value=""> Non</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Style autour du commentaire <small class="text-muted">Classe CSS</small></label>
                                <div class="col-sm-3">
                                    <input type="text"  class="form-control" name="classe" value="{{ $newsletter->classe }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Position de l'analyse</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline"><input {{ $newsletter->display == 'top' ? 'checked' : '' }} type="radio" name="display" value="top"> Avant arrêt</label>
                                    <label class="radio-inline"><input {{ $newsletter->display == 'bottom' ? 'checked' : '' }} type="radio" name="display" value="bottom"> Après arrêt</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cacher les titres de pictos</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline"><input {{ $newsletter->hide_title ? 'checked' : '' }} type="radio" name="hide_title" value="1"> Oui</label>
                                    <label class="radio-inline"><input {{ !$newsletter->hide_title ? 'checked' : '' }} type="radio" name="hide_title" value=""> Non</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                       <div class="row">
                          <div class="col-sm-3"></div>
                          <div class="col-sm-6">
                              <input type="hidden" name="id" value="{{ $newsletter->id }}">
                              <button class="btn btn-primary" type="submit">Envoyer</button>
                          </div>
                       </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop
