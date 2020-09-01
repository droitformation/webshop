@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-10">
            <h3>Rabais pour colloque</h3>
        </div>
        <div class="col-md-2 text-right">
            <a id="addRabais" href="{{ url('admin/rabais/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
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
                                <th>Valeur</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$rabais->isEmpty())
                            @foreach($rabais as $row)
                                <tr>
                                    <td><a href="{{ url('admin/rabais/'.$row->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>
                                        {!! $row->global ? '<span class="label label-warning">global</span> ' : '' !!}
                                        {{ $row->title }}
                                    </td>
                                    <td>{{ $row->value.' CHF' }}</td>
                                    <td>{{ $row->expire_at ? $row->expire_at->formatLocalized('%d %B %Y') : '' }}</td>
                                    <td class="text-right">
                                        @if($row->inscriptions->isEmpty())
                                            <form action="{{ url('admin/rabais/'.$row->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button id="deleteRabais_{{ $row->id }}" data-what="Supprimer" data-action="{{ $row->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                            </form>
                                        @else
                                            <span class="text-danger">Rabais utilisé</span>
                                        @endif
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