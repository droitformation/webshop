@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-10">

            <h3>Seminaires <a href="{{ url('admin/seminaire/create') }}" id="addSeminaire" class="btn btn-success pull-right"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></h3>

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <table class="table simple-table">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Image</th>
                                <th>Titre</th>
                                <th>Année</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$seminaires->isEmpty())
                            @foreach($seminaires as $seminaire)
                                <tr>
                                    <td><a href="{{ url('admin/seminaire/'.$seminaire->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td><img style="max-height: 50px;" src="{{ secure_asset('files/seminaires/'.$seminaire->image) }}" alt="{{ $seminaire->title }}" /></td>
                                    <td>{{ $seminaire->title }}</td>
                                    <td>{{ $seminaire->year }} </td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/seminaire/'.$seminaire->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $seminaire->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <div class="col-md-2">
            @include('backend.partials.sites-menu')
        </div>
    </div>

@stop