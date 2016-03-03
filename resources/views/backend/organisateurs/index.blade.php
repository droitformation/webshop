@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-4">
        <h3>Organisateurs</h3>
    </div>
    <div class="col-md-8 text-right">
        <a href="{{ url('admin/organisateur/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        @if(!$organisateurs->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Nom</th>
                            <th class="col-sm-2">Description</th>
                            <th class="col-sm-2">Lien</th>
                            <th class="col-sm-2">Logo</th>
                            <th class="col-sm-1">Centre</th>
                            <th class="col-sm-1 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                       
                            @foreach($organisateurs as $organisateur)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/organisateur/'.$organisateur->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><strong>{{ $organisateur->name }}</strong></td>
                                    <td>{!! $organisateur->description !!} </td>
                                    <td>{!! $organisateur->url !!}</td>
                                    <td>
                                        @if($organisateur->logo)
                                            <img style="height: 50px;" src="{{ asset('files/logos/'.$organisateur->logo) }}" alt="{{ $organisateur->name }}"></td>
                                        @endif
                                    <td>
                                        @if($organisateur->centre)
                                            <h1 class="label label-success" style="font-size: 90%;">Oui</h1>
                                        @else
                                            <h1 class="label label-default" style="font-size: 90%;">Non</h1>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/organisateur/'.$organisateur->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $organisateur->name }}" class="btn btn-danger btn-sm deleteAction">x</button>
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