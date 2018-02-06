@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-3">
            <h3>Listes hors campagne</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">
                    @if(isset($lists) && !$lists->isEmpty())
                    <ul class="list-group list-group-import">
                        @foreach($lists as $list)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-1"><a class="btn btn-sm btn-info" href="{{ url('build/liste/'.$list->id) }}"><i class="fa fa-pencil"></i></a></div>
                                    <div class="col-md-6"><a href="{{ url('build/liste/'.$list->id) }}">{{ $list->title }}</a></div>
                                    <div class="col-md-2">
                                        {{ $list->specialisations->implode('title',',') }}
                                    </div>
                                    <div class="col-md-2"><span class="label label-default pull-right">{{ $list->created_at->format('Y-m-d') }}</span></div>
                                    <div class="col-md-1">
                                        <form action="{{ url('build/liste/'.$list->id) }}" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="supprimer" data-action="menu: {{ $list->title }}" class="btn btn-danger btn-xs pull-right deleteAction">x</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="panel panel-primary">
                <form action="{{ url('build/liste') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4>Importer une liste</h4>

                        <div class="form-group">
                            <label for="type" class="col-sm-3 control-label">Titre de la liste</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ old('title') }}" required name="title" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Fichier excel</label>
                            <div class="col-sm-8">
                                <input type="file" required name="file">
                            </div>
                        </div>

                        @if(!$specialisations->isEmpty())
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Attacher des spécialisations</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="specialisations[]" multiple>
                                        <option value="">Aucune</option>
                                        @foreach($specialisations as $specialisation)
                                            <option value="{{ $specialisation->id }}">{{ $specialisation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <hr/>
                        <p><strong>Format du fichier excel:</strong></p>
                        <p>Dans la première case de la colonne mettre "email" puis continuer avec la liste des emails.</p>
                        <table class="table table-condensed table-bordered" style="width: auto;">
                            <tr><th>email</th></tr>
                            <tr><td>nom.prenom@domaine.ch</td></tr>
                            <tr><td>nom.prenom@domaine.ch</td></tr>
                            <tr><td>etc...</td></tr>
                        </table>

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3"><input type="hidden" value="" name="newsletter_id"></div>
                            <div class="col-sm-8 text-right">
                                <button class="btn btn-primary" id="importList" type="submit">Envoyer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
