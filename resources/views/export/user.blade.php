@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <h3>Exporter</h3>

            <div class="row">

                <div class="col-md-12">
                    <form action="{{ url('admin/user/search') }}" method="post">

                        <div class="panel panel-midnightblue">
                            <div class="panel-body">

                                <h4><i class="fa fa-globe"></i> &nbsp;Pays</h4>
                                <fieldset class="container-export">
                                    <div class="form-group">
                                        @if(!$pays->isEmpty())
                                            <div class="col-md-4">
                                                <select class="form-control" name="pays">
                                                    <option value="">-- Choix --</option>
                                                    @foreach($pays as $p)
                                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div><br/>
                                </fieldset>

                                <h4><i class="fa fa-map"></i> &nbsp;Cantons</h4>
                                @include('export.partials.item', ['name' => 'canton[]', 'items' => $cantons])

                                <h4><i class="fa fa-cubes"></i> &nbsp;Professions</h4>
                                @include('export.partials.item', ['name' => 'profession[]', 'items' => $professions])

                                <h4><i class="fa fa-bookmark"></i> &nbsp;Membres</h4>
                                @include('export.partials.item', ['name' => 'member[]', 'items' => $members])

                                <h4><i class="fa fa-tags"></i> &nbsp;Spécialisations</h4>
                                @include('export.partials.item', ['name' => 'specialisation[]', 'items' => $specialisations])

                            </div>
                        </div>

                        <div class="panel panel-midnightblue">
                            <div class="panel-body">

                                <h4>Dernier comptes crées</h4>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="col-sm-1">Action</th>
                                        <th class="col-sm-3">Nom</th>
                                        <th class="col-sm-3">Email</th>
                                        <th class="col-sm-2"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="selects">
                                    @if(!empty($users))
                                        @foreach($users as $user)
                                            <tr>
                                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/user/'.$user->id) }}">&Eacute;diter</a></td>
                                                <td><strong>{{ $user->name }}</strong></td>
                                                <td>{{ $user->email }}</td>
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
                        <button type="submit" class="btn btn-default">Rechercher</button>
                    </form>

                </div>

            </div>

        </div>
    </div>

@stop