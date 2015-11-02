@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-right" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/shipping/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Frais de port</h4>
                </div>
                <div class="panel-body">

                    <table class="table">
                        <tr>
                            <th>Action</th>
                            <th>Titre</th>
                            <th>Poids maximum</th>
                            <th>Prix</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                        @if(!$shippings->isEmpty())
                            @foreach($shippings as $shipping)
                                <tr>
                                    <td><a href="{{ url('admin/shipping/'.$shipping->id) }}" class="btn btn-sm btn-info">éditer</a></td>
                                    <td>{{ $shipping->title }}</td>
                                    <td>{{ $shipping->value }} grammes</td>
                                    <td>{{ $shipping->price_cents }}</td>
                                    <td>{{ $shipping->type }}</td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/shipping/'.$shipping->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-action="{{ $shipping->title }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>

                </div>
            </div>

        </div>
    </div>

@stop