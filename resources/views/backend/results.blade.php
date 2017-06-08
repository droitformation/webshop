@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3>Utilisateur/Adresse</h3>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <h4>Rechercher</h4>
                    <form action="{{ url('admin/search/user') }}" method="post">{!! csrf_field() !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="term" placeholder="Recherche...">
                              <span class="input-group-btn">
                                 <button type="submit" name="search" class="btn btn-info">OK</button>
                              </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
            </div>

            @if (session()->has('term'))
                <div class="panel panel-default">
                    <div class="panel-body">
                        <label><i class="fa fa-search"></i> &nbsp;Terme:</label>
                        <h4>{{ session()->get('term') }}</h4>
                    </div>
                </div>
            @endif

            @if(!session()->has('term'))
                <div class="alert alert-warning" role="alert">
                    <p>Aucun terme de recherche indiqué</p>
                </div>
            @endif

        </div>

        <div class="col-md-9">

            @if(isset($users) && !$users->isEmpty())
                <div class="panel panel-midnightblue">
                    <div class="panel-body">
                        <h4><i class="fa fa-users"></i> &nbsp;Résultats comptes</h4>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-4">Nom</th>
                                <th class="col-sm-4">Email</th>
                                <th class="col-sm-3"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                                @foreach($users as $user)
                                    <tr class="mainRowSearch">
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/user/'.$user->id.'?path') }}">&Eacute;diter</a></td>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/user/'.$user->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <input type="hidden" name="url" value="{{ url('admin/search/user') }}">
                                                <input type="hidden" name="term" value="{{ session()->get('term') }}">
                                                <a href="{{ url('admin/user/confirm/'.$user->id) }}" class="btn btn-danger btn-sm">Supprimer</a>
                                            </form>
                                        </td>
                                    </tr>
                                    @if(!$user->adresses->isEmpty())
                                        @foreach($user->adresses as $adresse)
                                            <tr class="secondRowSearch">
                                                <td class="text-right"><h3><i class="fa fa-map-marker" aria-hidden="true"></i></h3></td>
                                                <td><strong>{{ $adresse->name }}</strong><br/>{{ $adresse->company }}</td>
                                                <td>{{ $adresse->adresse }}</td>
                                                <td>{{ $adresse->npa }} {{ $adresse->ville }}</td>
                                            </tr>
                                        @endforeach
                                        <tr style="border:none; border-top:1px solid #b1b1b1;  "><td colspan="4"></td></tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(isset($adresses) && !$adresses->isEmpty())
                <div class="panel panel-midnightblue">
                    <div class="panel-body">
                        <h4><i class="fa fa-home"></i> &nbsp;Résultats adresses</h4>
                        <table class="table" style="margin-bottom: 0px;" >
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-3">Nom</th>
                                <th class="col-sm-2">Email</th>
                                <th class="col-sm-2">Entreprise</th>
                                <th class="col-sm-3">Ville</th>
                                <th class="col-sm-1"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                                @foreach($adresses as $adresse)
                                    <tr>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/adresse/'.$adresse->id) }}">&Eacute;diter</a></td>
                                        <td><strong>{{ $adresse->name }}</strong></td>
                                        <td>{{ $adresse->email }}</td>
                                        <td>{{ $adresse->company }}</td>
                                        <td>{{ $adresse->ville }}</td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/adresse/'.$adresse->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <input type="hidden" name="url" value="{{ url('admin/search/user') }}">
                                                <input type="hidden" name="term" value="{{ session()->get('term') }}">
                                                <button data-what="Supprimer" data-action="{{ $adresse->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(isset($users) && $users->isEmpty() && isset($adresses) && $adresses->isEmpty())
                <div class="alert alert-warning" role="alert">
                    <p>Rien trouvé</p>
                </div>
            @endif

        </div>
    </div>

@stop