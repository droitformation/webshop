@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-4">
        <h3>Thèmes</h3>
    </div>
    <div class="col-md-2">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/theme/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xs-12">

        @if(!$themes->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <table class="table simple-table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Titre</th>
                            <th class="col-sm-2 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                       
                            @foreach($themes as $theme)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/theme/'.$theme->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><strong>{{ $theme->title }}</strong></td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/theme/'.$theme->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $theme->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
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