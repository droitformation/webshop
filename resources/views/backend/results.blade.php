@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <h3>Utilisateur/Adresse</h3>

            <div class="row">

                <div class="col-md-3">
                    <div class="panel panel-midnightblue">
                        <div class="panel-body">
                            <h4>Rechercher par</h4>
                            <form action="{{ url('admin/search/user') }}" method="post">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Prénom">
                                </div>
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Nom">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email">
                                </div>
                                <button type="submit" class="btn btn-default">Rechercher</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">

                    @if(isset($duplicates) && !$duplicates->isEmpty())
                        <div class="panel panel-warning">
                            <div class="panel-body">
                                <h4><i class="fa fa-users"></i> &nbsp;Duplicatas</h4>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="col-sm-1">Action</th>
                                        <th class="col-sm-3">Nom</th>
                                        <th class="col-sm-3">Email</th>
                                        <th class="col-sm-2">Original</th>
                                        <th class="col-sm-3"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="selects">
                                    @foreach($duplicates as $duplicate)
                                        <tr>
                                            <td><a class="btn btn-sky btn-sm" href="{{ url('admin/duplicate/'.$duplicate->id) }}">&Eacute;diter</a></td>
                                            <td><strong>{{ $duplicate->name }}</strong></td>
                                            <td>{{ $duplicate->email }}</td>
                                            <td><button type="button" class="btn btn-sm btn-warning" data-toggle="popover" title="{{ isset($duplicate->user) ? $duplicate->user->name : 'beuh' }}" data-content="{{ isset($duplicate->user) ? $duplicate->user->email : '' }}">Original</button></td>
                                            <td class="text-right">
                                                {!! Form::open(array('route' => array('admin.duplicate.destroy', $duplicate->id), 'method' => 'delete')) !!}
                                                <button data-action="{{ $duplicate->name }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

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
                                            <tr>
                                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/user/'.$user->id) }}">&Eacute;diter</a></td>
                                                <td><strong>{{ $user->name }}</strong></td>
                                                <td>{{ $user->email }}</td>
                                                <td class="text-right">
                                                    {!! Form::open(array('route' => array('admin.user.destroy', $user->id), 'method' => 'delete')) !!}
                                                    <button data-action="{{ $user->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
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
                                        <th class="col-sm-3">Email</th>
                                        <th class="col-sm-2">Entreprise</th>
                                        <th class="col-sm-2">Ville</th>
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
                                                    {!! Form::open(array('route' => array('admin.adresse.destroy', $adresse->id), 'method' => 'delete')) !!}
                                                    <button data-action="{{ $adresse->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

@stop