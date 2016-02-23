@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-4">
        <h3>Lieux</h3>
    </div>
    <div class="col-md-8">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/location/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        @if(!$locations->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Carte</th>
                            <th class="col-sm-3">Nom</th>
                            <th class="col-sm-3">Adresse</th>
                            <th class="col-sm-2 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                       
                            @foreach($locations as $location)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/location/'.$location->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><img style="height: 40px;" src="{{ asset($location->location_map) }}" alt="Pas de carte"></td>
                                    <td><strong>{{ $location->name }}</strong></td>
                                    <td>{!! $location->adresse !!}</td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/location/'.$location->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $location->name }}" class="btn btn-danger btn-sm deleteAction">x</button>
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