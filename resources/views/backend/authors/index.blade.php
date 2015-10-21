@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/author/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4><i class="fa fa-tasks"></i> &nbsp;Auteur</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0px;" id="generic">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Nom</th>
                            <th class="col-sm-5">Occupation</th>
                            <th class="col-sm-2 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                        @if(!empty($authors))
                            @foreach($authors as $author)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/author/'.$author->id) }}">&Eacute;diter</a></td>
                                    <td><strong>{{ $author->name }}</strong></td>
                                    <td>{{ $author->occupation }}</td>
                                    <td class="text-right">
                                        {!! Form::open(array('route' => array('admin.author.destroy', $author->id), 'method' => 'delete')) !!}
                                        <button data-action="{{ $author->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                        {!! Form::close() !!}
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
</div>

@stop