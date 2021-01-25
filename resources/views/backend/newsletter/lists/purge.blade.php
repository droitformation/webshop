@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
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
        <div class="col-md-6">

            <form action="{{ url('build/invalid') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">{!! csrf_field() !!}
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <h4>Vérifier les emails</h4><br>
                        <p class="text-danger">Ne fonctionne que s'il existe assez de crédits sur debounce.io.</p>
                        <p class="text-danger">Les adresse email @unine.ch seront marqués comme spam si on en envoie plus de 100 par fichier!</p><br>
                        <div class="form-group mt-10">
                            <label for="message" class="col-sm-3 control-label"><strong>Fichier excel</strong></label>
                            <div class="col-sm-8">
                                <div class="well">
                                    <input type="file" required name="file">
                                </div>
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
                </div>
             </form>

        </div>
    </div>

@stop
