@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-right" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/abo/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Abos</h4>
                </div>
                <div class="panel-body">

                    <table class="table" id="generic">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Produit</th>
                                <th>Récurrence</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!$abos->isEmpty())
                            @foreach($abos as $abo)
                                <tr>
                                    <td><a href="{{ url('admin/abo/'.$abo->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $abo->product->title }}</td>
                                    <td>{{ $abo->plan }}</td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/abo/'.$abo->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $abo->title }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
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
    </div>

@stop