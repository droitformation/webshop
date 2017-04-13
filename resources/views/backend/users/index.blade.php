@extends('backend.layouts.master')
@section('content')


    <div class="row">
        <div class="col-md-6">
            <h3>Utilisateurs</h3>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ url('admin/user/create') }}" class="btn btn-success" id="addUser"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-7">
                            <h4><i class="fa fa-home"></i> &nbsp;Utilisateurs</h4>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ url('admin/users') }}" method="post">{!! csrf_field() !!}
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $term ? $term :'' }}" name="term" placeholder="Recherche...">
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                                </div><!-- /input-group -->
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">

                        @if(!$users->isEmpty())

                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="col-sm-1">Action</th>
                                    <th class="col-sm-3">Prénom</th>
                                    <th class="col-sm-3">Nom</th>
                                    <th class="col-sm-4">Email</th>
                                    <th class="col-sm-1"></th>
                                </tr>
                                </thead>
                                <tbody class="selects">
                                    @foreach($users as $user)
                                        <tr>
                                            <td><a class="btn btn-sky btn-sm" href="{{ url('admin/user/'.$user->id) }}">&Eacute;diter</a></td>
                                            <td><strong>{{ $user->first_name }}</strong></td>
                                            <td><strong>{{ $user->last_name }}</strong></td>
                                            <td>{{ $user->email }}</td>
                                            <td class="text-right">
                                                <form action="{{ url('admin/user/'.$user->id) }}" method="POST" class="form-horizontal">
                                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                    <input type="hidden" name="term" value="{{ session()->get('term') }}">
                                                    <input type="hidden" name="url" value="{{ url('admin/users') }}">
                                                    <button data-what="Supprimer" data-action="{{ $user->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator )
                                {{ $users->links() }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop