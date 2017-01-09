@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Adresses</h3>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url('admin/adresse/create') }}" class="btn btn-success" id="addAdresse"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-7">
                        <h4><i class="fa fa-home"></i> &nbsp;Adresses</h4>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ url('admin/adresses') }}" method="post">{!! csrf_field() !!}
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
                    @if(!$adresses->isEmpty())

                        <table class="table" style="margin-bottom: 0px;" >
                            <thead>
                            <tr>
                                <th class="col-sm-1">Action</th>
                                <th class="col-sm-1">Type</th>
                                <th class="col-sm-3">Nom</th>
                                <th class="col-sm-2">Email</th>
                                <th class="col-sm-2">Entreprise</th>
                                <th class="col-sm-3">Ville</th>
                                <th class="col-sm-1"></th>
                            </tr>
                            </thead>
                            <tbody class="selects">
                                @foreach($adresses as $adresse)
                                    <tr>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/adresse/'.$adresse->id) }}">&Eacute;diter</a></td>
                                        <td>{!! $adresse->user_id > 0 ? '<span class="label label-info">Compte</span>' : '<span class="label label-success">Adresse simple</span>' !!}</td>
                                        <td><strong>{{ $adresse->name }}</strong></td>
                                        <td>{{ $adresse->email }}</td>
                                        <td>{{ $adresse->company }}</td>
                                        <td>{{ $adresse->ville }}</td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/adresse/'.$adresse->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <input type="hidden" name="url" value="{{ url('admin/adresses') }}">
                                                <input type="hidden" name="term" value="{{ session()->get('term') }}">
                                                <button data-what="Supprimer" data-action="{{ $adresse->name }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($adresses instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{$adresses->links()}}
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@stop