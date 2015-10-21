@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/contenu/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4><i class="fa fa-tasks"></i> &nbsp;Contenus</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0px;" id="content-table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-2">Titre</th>
                            <th class="col-sm-1">Image</th>
                            <th class="col-sm-2">Type</th>
                            <th class="col-sm-2">Position</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                            @if(!empty($content))
                                @foreach($content as $item)
                                    <tr>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/contenu/'.$item->id) }}">&Eacute;diter</a></td>
                                        <td><strong>{{{ $item->titre or '' }}}</strong></td>
                                        <td>
                                            @if(isset($item->image))
                                                <img height="60" src="{{ asset('files/'.$item->image) }}" alt="{{{ $item->titre or '' }}}" />
                                            @endif
                                        </td>
                                        <td>{{{ $item->type or '' }}}</td>
                                        <td>{{ $positions[$item->position] }}</td>
                                        <td class="text-right">
                                            {!! Form::open(array('route' => array('admin.contenu.destroy', $item->id), 'method' => 'delete')) !!}
                                                <button data-what="supprimer" data-action="contenu: {{{ $item->titre }}}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
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