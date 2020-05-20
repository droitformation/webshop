@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Comptes</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a id="addCompte" href="{{ url('admin/compte/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        @if(!$comptes->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <table class="table simple-table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-2">Motif</th>
                            <th class="col-sm-2">Identifiant unique</th>
                            <th class="col-sm-2">Compte</th>
                            <th class="col-sm-2">Compte</th>
                            <th class="col-sm-1 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                            @foreach($comptes as $compte)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/compte/'.$compte->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><strong>{!! $compte->motif !!}</strong></td>
                                    <td><strong>{!! $compte->centre !!}</strong></td>
                                    <td><strong>{!! $compte->adresse !!}</strong></td>
                                    <td><strong>{{ $compte->compte }}</strong></td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/compte/'.$compte->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $compte->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
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


@stop