@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-10">
            <h3>Modèles de sondage</h3>
        </div>
        <div class="col-md-2 text-right">
            <a href="{{ url('admin/modele/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <table class="table simple-table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Titre</th>
                                <th>Desscription</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$modeles->isEmpty())
                            @foreach($modeles as $modele)
                                <tr>
                                    <td><a href="{{ url('admin/modele/'.$modele->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $modele->title }}</td>
                                    <td>{{ $modele->description }}</td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/modele/'.$modele->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $modele->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@stop