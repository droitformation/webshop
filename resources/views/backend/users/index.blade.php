@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/user/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
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
                            <th class="col-sm-5">Role</th>
                            <th class="col-sm-5">Numéro</th>
                            <th class="col-sm-2"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                        @if(!empty($users))
                            @foreach($users as $user)
                            <tr>
                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/user/'.$user->id) }}">&Eacute;diter</a></td>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>
                                    <?php $color = ($user->role == 'admin' || $user->role == 'invite' ? 'primary' : 'warning'); ?>
                                    <span class="label label-{{$color}}">{{ $user->role }}</span>
                                </td>
                                <td>{{ $user->numero or '' }}</td>
                                <td class="text-right">
                                    {!! Form::open(array('route' => array('admin.user.destroy', $user->id), 'method' => 'delete')) !!}
                                    <button data-action="{{ $user->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
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