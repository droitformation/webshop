@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <p><a href="{{ url('build/liste') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux listes</a></p>
        </div>
    </div>

    @if(isset($list))
    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-primary">
                <div class="panel-body">

                    <h4>{{ $list->title }}</h4>

                    <div class="btn-group pull-right">
                        <form action="{{ url('build/export') }}" method="POST">{!! csrf_field() !!}
                            <input type="hidden" name="list_id" value="{{ $list->id }}">
                            <button class="btn btn-sm btn-inverse" type="submit">Exporter (.xlsx)</button>
                            <button class="btn btn-sm btn-success" data-toggle="collapse" href="#addEmail" type="button">Ajouter un email</button>
                        </form>
                    </div>

                    @if(!$list->specialisations->isEmpty())
                        @foreach($list->specialisations as $specialisation)
                            <span style="font-size: 14px;" class="label label-warning">{{ $specialisation->title }}</span>
                        @endforeach
                    @endif

                    <div id="addEmail" class="collapse">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ url('build/emails') }}" method="POST">{!! csrf_field() !!}
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="email" class="form-control" placeholder="Ajouter un nouvel email">
                                            <input type="hidden" name="list_id" value="{{ $list->id }}">
                                            <span class="input-group-btn"><button class="btn btn-info" type="submit">Ajouter</button></span>
                                        </div><!-- /input-group -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <br/>

                    @if(!$list->emails->isEmpty())

                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-sm-10">Email</th>
                                <th class="col-sm-2 no-sort"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                            @foreach($list->emails as $email)
                                <tr>
                                    <td>{{ $email->email }}</td>
                                    <td class="text-right">
                                        <form action="{{ url('build/emails/'.$email->id) }}" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $email->email }}" class="btn btn-danger btn-xs pull-right deleteAction">x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Aucun email dans cette liste</p>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <form action="{{ url('build/liste/'.$list->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4>Importer d'autres emails dans la liste</h4>

                        <div class="form-group">
                            <label for="type" class="col-sm-3 control-label">Titre de la liste</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ $list->title }}" required name="title" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Fichier excel</label>
                            <div class="col-sm-8">
                                <input type="file" name="file">
                            </div>
                        </div>

                        @if(!$specialisations->isEmpty())
                            <div class="form-group">
                                <label for="message" class="col-sm-3 control-label">Attacher des spécialisations</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="specialisations[]" multiple>
                                        <option value="">Aucune</option>
                                        @foreach($specialisations as $specialisation)
                                            <option {{ $list->specialisations->contains('id',$specialisation->id) ? 'selected' : '' }} value="{{ $specialisation->id }}">{{ $specialisation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="hidden" value="" name="newsletter_id">
                                <input type="hidden" value="{{ $list->id }}" name="id">
                            </div>
                            <div class="col-sm-8 text-right">
                                <button class="btn btn-primary" type="submit">Envoyer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

@endsection