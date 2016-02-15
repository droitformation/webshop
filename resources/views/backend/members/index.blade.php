@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-4">
        <h3>Membres</h3>
    </div>
    <div class="col-md-2">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/member/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xs-12">

        @if(!$members->isEmpty())

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
                       
                            @foreach($members as $member)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/member/'.$member->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><strong>{{ $member->title }}</strong></td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/member/'.$member->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <input type="hidden" name="id" value="{{ $member->id }}">
                                            <button data-what="Supprimer" data-action="{{ $member->title }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
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