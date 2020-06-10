@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-3">
            <h3>Purger les email invalides des listes</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-primary">
                <form action="{{ url('build/purge') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4>Choisir une liste d'email invalides</h4><br>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Fichier excel</label>
                            <div class="col-sm-8">
                                <input type="file" required name="file">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Listes</label>
                            <div class="col-sm-8">
                                @if(!$newsletters->isEmpty())
                                    <select name="newsletter_id[]" class="form-control" multiple>
                                        @foreach($newsletters as $newsletter)
                                            <option value="{{ $newsletter->id }}">{{ $newsletter->titre }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8 text-right">
                                <button class="btn btn-primary" id="importList" type="submit">Envoyer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <p><strong>Format du fichier excel:</strong></p>
                    <p>Dans la première case de la colonne mettre "email" puis continuer avec la liste des emails.</p>
                    <table class="table table-condensed table-bordered" style="width: auto;">
                        <tr><th>email</th></tr>
                        <tr><td>nom.prenom@domaine.ch</td></tr>
                        <tr><td>nom.prenom@domaine.ch</td></tr>
                        <tr><td>etc...</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
