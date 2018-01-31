@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <h3>Exporter</h3>

            <div class="row">
                <div class="col-md-12">

                    <!-- START Export criteria -->
                    <div class="panel panel-midnightblue">
                        <fieldset class="panel-body">

                            <form action="{{ url('admin/export/search') }}" method="post">{!! csrf_field() !!}
                                <h4><i class="fa fa-globe"></i> &nbsp;Pays</h4>
                                <fieldset class="container-export">
                                    <div class="form-group">
                                        @if(!$pays->isEmpty())
                                            <div class="col-md-4">
                                                <select id="selectPays" class="form-control" name="pays">
                                                    <option value="">-- Choix --</option>
                                                    @foreach($pays as $p)
                                                        <option {{ ($p->id == 208 ? 'selected' : '') }} value="{{ $p->id }}">{{ $p->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div><br/>
                                </fieldset>
                                <div id="selectCantons">
                                    <h4><i class="fa fa-map"></i> &nbsp;Cantons</h4>
                                    <p class="text-right"><input type="checkbox" id="select_all" /> &nbsp;Séléctionner tous</p>
                                    @include('backend.export.partials.item', ['name' => 'cantons[]', 'items' => $cantons, 'class' => 'checkbox_all'])
                                </div>
                                <hr/>
                                <h4><i class="fa fa-cubes"></i> &nbsp;Professions</h4>
                                @include('backend.export.partials.item', ['name' => 'professions[]', 'items' => $professions])

                                <h4><i class="fa fa-bookmark"></i> &nbsp;Membres</h4>
                                @include('backend.export.partials.item', ['name' => 'members[]', 'items' => $members])

                                <h4><i class="fa fa-tags"></i> &nbsp;Spécialisations</h4>
                                @include('backend.export.partials.item', ['name' => 'specialisations[]', 'items' => $specialisations])

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label><input name="each" value="1" type="checkbox">  <strong>Afficher les résultats de tous les critères</strong></label>
                                    </div>
                                    <p class="help-block">Permet d'obtenir tous les utlisateurs et adresses qui ont tous les critères demandés sans recoupement.</p><br/>
                                </div>

                                <button type="submit" class="btn btn-lg btn-info">Rechercher</button>
                            </form>
                        </div>
                    </div>
                    <!-- END Export criteria -->
                    <!-- START results list -->
                    @if(isset($persones) && !$persones->isEmpty())
                        <div class="panel panel-midnightblue">
                            <div class="panel-body">
                                <h4>Résultats</h4>
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
                                        @foreach($persones as $user)
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <!-- END results list -->

                </div>
            </div>

        </div>
    </div>

@stop